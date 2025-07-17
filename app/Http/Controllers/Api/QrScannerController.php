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
            // Process scan
            $result = $this->qrScannerService->processScan(
                $request->input('qr_data'),
                CheckInOutAction::from($request->input('action')),
                Auth::user()->name ?? 'Unknown'
            );

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
