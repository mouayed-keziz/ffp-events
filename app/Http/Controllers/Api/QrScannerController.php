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
            'action' => 'required|string|in:check_in,check_out'
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
            $hostessUser = Auth::user();
            $scanUser = $hostessUser->name ?? 'Unknown';

            // Find badge by code (qr_data contains the badge code)
            $badge = Badge::where('code', $qrData)->first();

            if (!$badge) {
                $result = $this->qrScannerService->buildErrorResult('Access Denied: Badge not found');
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
                $result = $this->qrScannerService->buildErrorResult('Access Denied: Badge is not associated with any event');
                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            }

            // Check if the hostess is assigned to this event
            if (!$hostessUser->assignedEvents()->where('event_announcements.id', $event->id)->exists()) {
                $result = $this->qrScannerService->buildErrorResult('Access denied: You are not assigned to this event');
                return response()->json([
                    'success' => true,
                    'data' => $result
                ]);
            }

            // Process check-in/check-out logic
            DB::transaction(function () use ($badge, $action, $event, $hostessUser) {
                $this->processCheckAction($badge, $action, $event->id, $hostessUser);
            });

            // Build success result with real badge data
            $result = $this->qrScannerService->buildSuccessResult(
                $action,
                $badge->code,
                $scanUser,
                $badge->name,
                $badge->position,
                $badge->company,
                $badge->email
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

        // Create badge check log
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

        // Handle current attendee record
        $currentAttendee = CurrentAttendee::where('badge_id', $badge->id)
            ->where('event_announcement_id', $eventId)
            ->first();

        if ($action === CheckInOutAction::CHECK_IN) {
            // Check-in: Create or update current attendee record
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
            } else {
                // Update existing record (re-check-in)
                $currentAttendee->update([
                    'checked_in_at' => $now,
                    'checked_in_by_user_id' => $hostessUser->id,
                ]);
            }
        } else {
            // Check-out: Remove current attendee record
            if ($currentAttendee) {
                $currentAttendee->delete();
            }
        }
    }
}
