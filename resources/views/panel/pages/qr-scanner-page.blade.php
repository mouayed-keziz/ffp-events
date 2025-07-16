<x-filament::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            @include('panel.components.scanner.qr-scanner')
        </div>

        <div>
            @include('panel.components.scanner.qr-results', [
                'lastScannedCode' => $lastScannedCode,
                'scannedAt' => $scannedAt,
                'scanUser' => $scanUser,
            ])
        </div>
    </div>
</x-filament::page>
