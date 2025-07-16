<x-filament::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Scanner Component (1/2 width) -->
        <div>
            <x-qr-scanner />
        </div>

        <!-- Results Component (1/2 width) -->
        <div>
            <x-qr-results :lastScannedCode="$lastScannedCode" :scannedAt="$scannedAt" :scanUser="$scanUser" />
        </div>
    </div>
</x-filament::page>
