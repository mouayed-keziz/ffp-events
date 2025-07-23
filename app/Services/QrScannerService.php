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
        ?string $badgeEmail = null,
        ?string $statusMessage = null,
        ?string $totalTimeSpent = null
    ): array {
        $time = now()->format('Y-m-d H:i:s');
        $isCheckin = $action === CheckInOutAction::CHECK_IN;
        $statusText = $statusMessage ?? $action->getLabel();

        // Use real badge data if available, otherwise fallback to defaults
        $name = $badgeName ?? 'Ahmed Ben Salah';
        $position = $badgePosition ?? 'Senior Developer';
        $company = $badgeCompany ?? 'Tech Solutions Inc.';
        $email = $badgeEmail ?? 'ahmed@example.com';

        // Determine style based on whether action was taken
        $actionTaken = $statusMessage === null ||
            str_contains(strtolower($statusMessage), 'successfully');
        $headerStyle = $actionTaken ? 'highlight' : 'warning';
        $headerIcon = $actionTaken ? 'heroicon-s-check-circle' : 'heroicon-s-exclamation-triangle';

        $resultBlocks = [
            [
                'label' => __('panel/scanner.attendance_status'),
                'data' => $statusText . ' at ' . $time,
                'icon' => $headerIcon,
                'style' => $headerStyle,
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
                'data' => '<span class="inline-flex items-center rounded-full bg-' . ($isCheckin ? 'green' : 'yellow') . '-100 dark:bg-' . ($isCheckin ? 'green' : 'yellow') . '-900/30 px-2 py-1 text-xs font-medium text-' . ($isCheckin ? 'green' : 'yellow') . '-800 dark:text-' . ($isCheckin ? 'green' : 'yellow') . '-300">' . $action->getLabel() . '</span>',
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
        ];

        // Add time spent block if provided
        if ($totalTimeSpent) {
            $resultBlocks[] = [
                'label' => __('panel/scanner.time_spent'),
                'data' => $totalTimeSpent,
                'icon' => 'heroicon-o-clock',
                'style' => 'info',
                'type' => 'badge',
                'layout' => 'full'
            ];
        }

        return [
            'state' => 'success',
            'error_message' => '',
            'result_blocks' => $resultBlocks
        ];
    }
}
