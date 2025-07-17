<?php

namespace App\Services;

use App\Enums\CheckInOutAction;

class QrScannerService
{
    /**
     * Process QR code scan and return result data
     */
    public function processScan(string $qrData, CheckInOutAction $action, string $scanUser): array
    {
        // Demo logic: Check for specific demo case
        if (trim(strtolower($qrData)) === 'http://en.m.wikipedia.org') {
            return $this->buildSuccessResult($action, $qrData, $scanUser);
        }

        return $this->buildErrorResult('Access Denied: Invalid badge detected');
    }

    /**
     * Build error result response
     */
    private function buildErrorResult(string $message): array
    {
        return [
            'state' => 'error',
            'error_message' => $message,
            'result_blocks' => []
        ];
    }

    /**
     * Build success result response
     */
    private function buildSuccessResult(CheckInOutAction $action, string $badgeCode, string $scanUser): array
    {
        $time = now()->format('Y-m-d H:i:s');
        $isCheckin = $action === CheckInOutAction::CHECK_IN;
        $statusText = $action->getLabel();

        return [
            'state' => 'success',
            'error_message' => '',
            'result_blocks' => [
                [
                    'label' => __('panel/scanner.attendance_status'),
                    'data' => $statusText . ' at ' . $time,
                    'icon' => 'heroicon-s-check-circle',
                    'style' => 'highlight',
                    'type' => 'card',
                    'layout' => 'full'
                ],
                [
                    'label' => __('panel/scanner.badge_code'),
                    'data' => $badgeCode,
                    'icon' => 'heroicon-o-qr-code',
                    'style' => 'default',
                    'type' => 'raw',
                    'layout' => 'full'
                ],
                [
                    'label' => __('panel/scanner.name'),
                    'data' => 'Ahmed Ben Salah',
                    'icon' => 'heroicon-o-user',
                    'style' => 'info',
                    'type' => 'badge',
                    'layout' => 'grid'
                ],
                [
                    'label' => __('panel/scanner.position'),
                    'data' => 'Senior Developer',
                    'icon' => 'heroicon-o-briefcase',
                    'style' => 'info',
                    'type' => 'badge',
                    'layout' => 'grid'
                ],
                [
                    'label' => __('panel/scanner.company'),
                    'data' => 'Tech Solutions Inc.',
                    'icon' => 'heroicon-o-building-office',
                    'style' => 'default',
                    'type' => 'badge',
                    'layout' => 'grid',
                    'colSpan' => 2
                ],
                [
                    'label' => __('panel/scanner.status'),
                    'data' => '<span class="inline-flex items-center rounded-full bg-' . ($isCheckin ? 'green' : 'yellow') . '-100 dark:bg-' . ($isCheckin ? 'green' : 'yellow') . '-900/30 px-2 py-1 text-xs font-medium text-' . ($isCheckin ? 'green' : 'yellow') . '-800 dark:text-' . ($isCheckin ? 'green' : 'yellow') . '-300">' . $statusText . '</span>',
                    'icon' => $action->getIcon(),
                    'style' => $isCheckin ? 'success' : 'warning',
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
