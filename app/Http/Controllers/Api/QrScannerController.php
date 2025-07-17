<?php

namespace App\Http\Controllers\Api;

use App\Enums\CheckInOutAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class QrScannerController extends Controller
{
    /**
     * Process QR code scan
     */
    public function processScan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_data' => 'required|string',
            'action' => 'required|string|in:check_in,check_out'
        ]);

        // return [
        //     "data" => $request->all(),
        //     "message" => "hello from process scan api"
        // ];
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
            $scanUser = Auth::user()->name ?? 'Unknown';
            $scanTime = now()->format('Y-m-d H:i:s');

            // Process the scan result (similar to the original Livewire logic)
            $result = $this->processScanResult($qrData, $action, $scanUser, $scanTime);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('QR Scanner Error: ' . $e->getMessage(), [
                'qr_data' => $request->input('qr_data'),
                'action' => $request->input('action'),
                'user' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process QR code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process scan result (converted from Livewire logic)
     */
    protected function processScanResult($qrData, CheckInOutAction $action, $scanUser, $scanTime)
    {
        try {
            // Demo logic: Check for specific demo case
            if (trim(strtolower($qrData)) !== 'keziz mouayed') {
                return $this->buildErrorResult('Access Denied: Invalid badge detected');
            }

            return $this->buildAttendanceResult(
                $action,
                $scanTime,
                'Ahmed Ben Salah', // name from badge data or database
                'Senior Developer', // position
                'Tech Solutions Inc.', // company
                $qrData, // badge code
                $scanUser
            );
        } catch (\Exception $e) {
            return $this->buildErrorResult('Failed to process QR code: ' . $e->getMessage());
        }
    }

    /**
     * Build error result response
     */
    protected function buildErrorResult($message = null)
    {
        return [
            'state' => 'error',
            'error_message' => $message ?: 'An error occurred while processing the badge',
            'result_blocks' => []
        ];
    }

    /**
     * Build attendance result response
     */
    protected function buildAttendanceResult(CheckInOutAction $action, $time, $name, $position, $company, $badgeCode, $scanUser)
    {
        $isCheckin = $action === CheckInOutAction::CHECK_IN;
        $statusText = $action->getLabel();
        $statusStyle = $isCheckin ? 'success' : 'warning';
        $statusIcon = $action->getIcon();

        return [
            'state' => 'success',
            'error_message' => '',
            'result_blocks' => [
                // Highlight block (full width)
                [
                    'label' => __('panel/scanner.attendance_status'),
                    'data' => $statusText . ' at ' . $time,
                    'icon' => 'heroicon-s-check-circle',
                    'style' => 'highlight',
                    'type' => 'card',
                    'layout' => 'full'
                ],
                // Badge code (full width)
                [
                    'label' => __('panel/scanner.badge_code'),
                    'data' => $badgeCode,
                    'icon' => 'heroicon-o-qr-code',
                    'style' => 'default',
                    'type' => 'raw',
                    'layout' => 'full'
                ],
                // Grid blocks for attendee information
                [
                    'label' => __('panel/scanner.name'),
                    'data' => $name,
                    'icon' => 'heroicon-o-user',
                    'style' => 'info',
                    'type' => 'badge',
                    'layout' => 'grid'
                ],
                [
                    'label' => __('panel/scanner.position'),
                    'data' => $position,
                    'icon' => 'heroicon-o-briefcase',
                    'style' => 'info',
                    'type' => 'badge',
                    'layout' => 'grid'
                ],
                [
                    'label' => __('panel/scanner.company'),
                    'data' => $company,
                    'icon' => 'heroicon-o-building-office',
                    'style' => 'default',
                    'type' => 'badge',
                    'layout' => 'grid',
                    'colSpan' => 2
                ],
                [
                    'label' => __('panel/scanner.status'),
                    'data' => '<span class="inline-flex items-center rounded-full bg-' . ($isCheckin ? 'green' : 'yellow') . '-100 dark:bg-' . ($isCheckin ? 'green' : 'yellow') . '-900/30 px-2 py-1 text-xs font-medium text-' . ($isCheckin ? 'green' : 'yellow') . '-800 dark:text-' . ($isCheckin ? 'green' : 'yellow') . '-300">' . $statusText . '</span>',
                    'icon' => $statusIcon,
                    'style' => $statusStyle,
                    'type' => 'badge',
                    'layout' => 'grid'
                ],
                [
                    'label' => __('panel/scanner.scanner_user'),
                    'data' => $scanUser,
                    'icon' => 'heroicon-o-user-circle',
                    'style' => 'default',
                    'type' => 'badge',
                    'layout' => 'grid'
                ]
            ]
        ];
    }
}
