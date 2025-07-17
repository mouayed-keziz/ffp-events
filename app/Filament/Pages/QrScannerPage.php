<?php

namespace App\Filament\Pages;

use App\Enums\CheckInOutAction;
use App\Enums\Role;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class QrScannerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static string $view = 'panel.pages.qr-scanner-page';
    // sort
    protected static ?int $navigationSort = 100;

    public $lastScannedCode = '';
    public $scanStatus = '';
    public $scannedAt = '';
    public $scanUser = '';
    public $resultState = 'empty'; // empty, success, error
    public $errorMessage = '';
    public $resultBlocks = [];
    public $currentAction = CheckInOutAction::CHECK_IN; // Default to check-in

    public static function getNavigationLabel(): string
    {
        return __('panel/scanner.navigation_label');
    }

    public function getTitle(): string
    {
        return __('panel/scanner.title');
    }

    public function getHeader(): View
    {
        return view('panel.pages.components.scanner-header', [
            'page' => $this,
        ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->hasRole([Role::HOSTESS->value]);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return Auth::user()->hasRole([Role::HOSTESS->value]);
    }

    #[On('qr-code-scanned')]
    public function handleQrCodeScanned($qrData)
    {
        $this->lastScannedCode = $qrData;
        $this->scannedAt = now()->format('Y-m-d H:i:s');
        $this->scanUser = Auth::user()->name ?? 'Unknown';
        $this->scanStatus = __('panel/scanner.scanned_at') . ' ' . now()->format('H:i:s');

        // Determine scan result and build result blocks
        $this->processScanResult($qrData);
    }

    public function toggleAction()
    {
        $this->currentAction = $this->currentAction === CheckInOutAction::CHECK_IN
            ? CheckInOutAction::CHECK_OUT
            : CheckInOutAction::CHECK_IN;
    }

    protected function processScanResult($qrData)
    {
        try {
            // Demo logic: Check for specific demo case
            if (trim(strtolower($qrData)) === 'keziz mouayed') {
                $this->setErrorResult('Access Denied: Invalid badge detected');
                return;
            }

            $this->setAttendanceResult(
                $this->currentAction,
                now()->format('Y-m-d H:i:s'),
                'Ahmed Ben Salah', // name from badge data or database
                'Senior Developer', // position
                'Tech Solutions Inc.', // company
                $qrData // badge code
            );
        } catch (\Exception $e) {
            $this->setErrorResult('Failed to process QR code: ' . $e->getMessage());
        }
    }

    /**
     * Utility function to set error result state
     * 
     * @param string $message Custom error message
     */
    protected function setErrorResult($message = null)
    {
        $this->resultState = 'error';
        $this->errorMessage = $message ?: 'An error occurred while processing the badge';
        $this->resultBlocks = [];
    }

    /**
     * Utility function to set attendance result state
     * 
     * @param CheckInOutAction $action Check-in or checkout action
     * @param string $time Check-in/checkout time
     * @param string $name Attendee name
     * @param string $position Attendee position
     * @param string $company Attendee company
     * @param string $badgeCode Badge code/QR data
     */
    protected function setAttendanceResult(CheckInOutAction $action, $time, $name, $position, $company, $badgeCode)
    {
        $this->resultState = 'success';
        $this->errorMessage = '';

        $isCheckin = $action === CheckInOutAction::CHECK_IN;
        $statusText = $action->getLabel();
        $statusStyle = $isCheckin ? 'success' : 'warning';
        $statusIcon = $action->getIcon();

        $this->resultBlocks = [
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
                'data' => $this->scanUser,
                'icon' => 'heroicon-o-user-circle',
                'style' => 'default',
                'type' => 'badge',
                'layout' => 'grid'
            ]
        ];
    }
}
