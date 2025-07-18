<?php

namespace App\Http\Controllers\Api;

use App\Enums\CheckInOutAction;
use App\Http\Controllers\Controller;
use App\Models\Badge;
use App\Models\BadgeCheckLog;
use App\Models\CurrentAttendee;
use App\Models\EventAnnouncement;
use App\Services\QrScannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QrScannerController extends Controller
{
    public function __construct(
        private QrScannerService $qrScannerService
    ) {}

    /**
     * Process QR code scan
     */
    public function processScan(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'qr_data' => 'required|string',
            'action' => 'required|string|in:check_in,check_out',
            'locale' => 'nullable|string|in:en,fr,ar'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $qrData = $request->input('qr_data');
            $action = CheckInOutAction::from($request->input('action'));
            $locale = $request->input('locale', 'en');
            $hostessUser = Auth::user();

            // Set application locale for this request
            app()->setLocale($locale);

            $scanUser = $hostessUser->name ?? 'Unknown';

            // Find badge by code (qr_data contains the badge code)
            $badge = Badge::where('code', $qrData)->first();

            if (!$badge) {
                $result = $this->qrScannerService->buildErrorResult(__('panel/scanner.badge_not_found'));
                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            }

            // Determine event from badge's submission
            $event = null;
            $eventSubmission = null;

            if ($badge->visitor_submission_id) {
                $visitorSubmission = $badge->visitorSubmission;
                if ($visitorSubmission && $visitorSubmission->eventAnnouncement) {
                    $event = $visitorSubmission->eventAnnouncement;
                    $eventSubmission = $visitorSubmission;
                }
            } elseif ($badge->exhibitor_submission_id) {
                $exhibitorSubmission = $badge->exhibitorSubmission;
                if ($exhibitorSubmission && $exhibitorSubmission->eventAnnouncement) {
                    $event = $exhibitorSubmission->eventAnnouncement;
                    $eventSubmission = $exhibitorSubmission;
                }
            }

            if (!$event || !$eventSubmission) {
                $result = $this->qrScannerService->buildErrorResult(__('panel/scanner.badge_not_associated'));
                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            }

            // Check if the hostess is assigned to this event
            if (!$hostessUser->assignedEvents()->where('event_announcements.id', $event->id)->exists()) {
                $result = $this->qrScannerService->buildErrorResult(__('panel/scanner.not_assigned_to_event'));
                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            }

            // Check current attendance status before processing
            $currentAttendee = CurrentAttendee::where('badge_id', $badge->id)
                ->where('event_announcement_id', $event->id)
                ->first();

            // Determine if action will result in a state change
            $willChangeState = false;
            $statusMessage = '';

            if ($action === CheckInOutAction::CHECK_IN) {
                if (!$currentAttendee) {
                    $willChangeState = true;
                    $statusMessage = __('panel/scanner.successfully_checked_in');
                } else {
                    $statusMessage = __('panel/scanner.already_checked_in');
                }
            } else { // CHECK_OUT
                if ($currentAttendee) {
                    $willChangeState = true;
                    $statusMessage = __('panel/scanner.successfully_checked_out');
                } else {
                    $statusMessage = __('panel/scanner.not_checked_in');
                }
            }

            // Process check-in/check-out logic only if state will change
            if ($willChangeState) {
                DB::transaction(function () use ($badge, $action, $event, $hostessUser) {
                    $this->processCheckAction($badge, $action, $event->id, $hostessUser);
                });
            }

            // Build success result with real badge data
            $result = $this->qrScannerService->buildSuccessResult(
                $action,
                $badge->code,
                $scanUser,
                $badge->name,
                $badge->position,
                $badge->company,
                $badge->email,
                $statusMessage
            );

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('QR Scanner Error: ' . $e->getMessage(), [
                'qr_data' => $request->input('qr_data'),
                'action' => $request->input('action'),
                'user' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process QR code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process the check-in or check-out action
     */
    private function processCheckAction(Badge $badge, CheckInOutAction $action, int $eventId, $hostessUser): void
    {
        $now = now();

        // Handle current attendee record
        $currentAttendee = CurrentAttendee::where('badge_id', $badge->id)
            ->where('event_announcement_id', $eventId)
            ->first();

        $shouldRecordAction = false;

        if ($action === CheckInOutAction::CHECK_IN) {
            // Check-in: Only proceed if person is not already checked in
            if (!$currentAttendee) {
                CurrentAttendee::create([
                    'event_announcement_id' => $eventId,
                    'badge_id' => $badge->id,
                    'checked_in_at' => $now,
                    'checked_in_by_user_id' => $hostessUser->id,
                    'badge_code' => $badge->code,
                    'badge_name' => $badge->name,
                    'badge_email' => $badge->email,
                    'badge_position' => $badge->position,
                    'badge_company' => $badge->company,
                ]);
                $shouldRecordAction = true; // Record log only when state changes
            }
            // If already checked in, do nothing (no duplicate check-in)
        } else {
            // Check-out: Only proceed if person is currently checked in
            if ($currentAttendee) {
                $currentAttendee->delete();
                $shouldRecordAction = true; // Record log only when state changes
            }
            // If not checked in, do nothing (no duplicate check-out)
        }

        // Create badge check log only when there's an actual state change
        if ($shouldRecordAction) {
            BadgeCheckLog::create([
                'event_announcement_id' => $eventId,
                'badge_id' => $badge->id,
                'checked_by_user_id' => $hostessUser->id,
                'action' => $action,
                'action_time' => $now,
                'badge_code' => $badge->code,
                'badge_name' => $badge->name,
                'badge_email' => $badge->email,
                'badge_position' => $badge->position,
                'badge_company' => $badge->company,
            ]);
        }
    }
}
