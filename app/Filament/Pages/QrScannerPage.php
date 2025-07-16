<?php

namespace App\Filament\Pages;

use App\Enums\Role;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class QrScannerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string $view = 'panel.pages.qr-scanner-page';
    protected static ?string $navigationLabel = 'QR Scanner';
    protected static ?string $title = 'QR Scanner';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole([Role::HOSTESS->value]);
    }
    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole([Role::HOSTESS->value]);
    }

    public bool $scannerActive = false;
    public string $lastScannedQr = '';

    #[On('qr-code-scanned')]
    public function handleQrCodeScan($qrData)
    {
        $this->lastScannedQr = $qrData;

        Log::info('QR Code scanned', [
            'qr_data' => $qrData,
            'timestamp' => now(),
        ]);
    }

    public function startScanner()
    {
        $this->scannerActive = true;
    }

    public function stopScanner()
    {
        $this->scannerActive = false;
    }
}
