<?php

namespace App\Filament\Pages;

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

    public $lastScannedCode = '';
    public $scanStatus = '';
    public $scannedAt = '';
    public $scanUser = '';

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

        // Debug the result
        // dd([
        //     'scanned_code' => $qrData,
        //     'timestamp' => now(),
        //     'user' => Auth::user()->name ?? 'Unknown'
        // ]);
    }
}
