<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;

class QrScannerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';
    protected static string $view = 'panel.pages.qr-scanner-page';
    protected static ?string $navigationLabel = 'QR Scanner';
    protected static ?string $title = 'QR Scanner';

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
