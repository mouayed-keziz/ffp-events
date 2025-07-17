<?php

namespace App\Http\Controllers\Api;

use App\Enums\CheckInOutAction;
use App\Http\Controllers\Controller;
use App\Services\QrScannerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $scanUser = Auth::user()->name ?? 'Unknown';

            // Business Logic: Decide if scan is valid or not
            if (trim(strtolower($qrData)) === 'http://en.m.wikipedia.org') {
                // Valid badge - build success result
                $result = $this->qrScannerService->buildSuccessResult($action, $qrData, $scanUser);
            } else {
                // Invalid badge - build error result
                $result = $this->qrScannerService->buildErrorResult('Access Denied: Invalid badge detected');
            }

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
}
