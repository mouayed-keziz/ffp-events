<?php

namespace App\Services;

use App\Enums\CheckInOutAction;

class QrScannerService
{
    /**
     * Build error result response
     */
    public function buildErrorResult(string $message): array
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
    public function buildSuccessResult(
        CheckInOutAction $action,
        string $badgeCode,
        string $scanUser,
        ?string $badgeName = null,
        ?string $badgePosition = null,
        ?string $badgeCompany = null,
        ?string $badgeEmail = null
    ): array {
        $time = now()->format('Y-m-d H:i:s');
        $isCheckin = $action === CheckInOutAction::CHECK_IN;
        $statusText = $action->getLabel();

        // Use real badge data if available, otherwise fallback to defaults
        $name = $badgeName ?? 'Ahmed Ben Salah';
        $position = $badgePosition ?? 'Senior Developer';
        $company = $badgeCompany ?? 'Tech Solutions Inc.';
        $email = $badgeEmail ?? 'ahmed@example.com';

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
                    'data' => $name,
                    'icon' => 'heroicon-o-user',
                    'style' => 'info',
                    'type' => 'badge',
                    'layout' => 'grid'
                ],
                [
                    'label' => __('panel/scanner.email'),
                    'data' => $email,
                    'icon' => 'heroicon-o-envelope',
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
