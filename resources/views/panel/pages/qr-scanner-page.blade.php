<x-filament-panels::page>
    <div class="w-full">
        <!-- QR Scanner Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    QR Scanner
                </h2>
                <div class="flex space-x-3">
                    @if ($scannerActive)
                        <x-filament::button wire:click="stopScanner" color="danger" icon="heroicon-o-stop">
                            Stop
                        </x-filament::button>
                    @else
                        <x-filament::button wire:click="startScanner" color="success" icon="heroicon-o-play">
                            Start
                        </x-filament::button>
                    @endif
                </div>
            </div>

            <!-- QR Scanner Container with Fixed Height -->
            <div class="relative max-w-md mx-auto">
                <div id="qr-reader"
                    class="w-full h-80 rounded-lg border-2 border-gray-200 dark:border-gray-600 {{ $scannerActive ? '' : 'hidden' }}">
                </div>

                @if (!$scannerActive)
                    <div
                        class="w-full h-80 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                        <div class="text-center">
                            <x-heroicon-o-qr-code class="w-16 h-16 mx-auto text-gray-400 mb-2" />
                            <p class="text-gray-500 dark:text-gray-400">Click "Start" to begin scanning</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Last Scanned QR -->
            @if ($lastScannedQr)
                <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg max-w-md mx-auto">
                    <p class="text-sm font-medium text-green-700 dark:text-green-300">Last Scanned:</p>
                    <p class="text-xs text-green-600 dark:text-green-400 font-mono break-all">{{ $lastScannedQr }}</p>
                </div>
            @endif

            <!-- Status Messages -->
            <div id="scanner-status" class="mt-4 max-w-md mx-auto"></div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
        <script>
            let html5QrCode = null;
            let scanning = false;

            function addStatus(message, type = 'info') {
                const container = document.getElementById('scanner-status');
                const div = document.createElement('div');
                const colors = {
                    success: 'bg-green-50 text-green-700 border-green-200',
                    error: 'bg-red-50 text-red-700 border-red-200',
                    info: 'bg-blue-50 text-blue-700 border-blue-200'
                };

                div.className = `p-2 rounded border text-sm mb-2 ${colors[type] || colors.info}`;
                div.textContent = message;
                container.appendChild(div);

                // Remove after 5 seconds
                setTimeout(() => div.remove(), 5000);
            }

            // Watch for Livewire updates
            document.addEventListener('livewire:updated', function() {
                const isActive = @json($scannerActive);
                console.log('Scanner state changed:', isActive);

                if (isActive && !scanning) {
                    startScanning();
                } else if (!isActive && scanning) {
                    stopScanning();
                }
            });

            async function startScanning() {
                console.log('Starting QR scanner...');
                addStatus('Starting scanner...', 'info');

                if (scanning) {
                    console.log('Already scanning');
                    return;
                }

                try {
                    scanning = true;
                    html5QrCode = new Html5Qrcode("qr-reader");

                    // Get available cameras first
                    const devices = await Html5Qrcode.getCameras();
                    console.log('Available cameras:', devices);
                    addStatus(`Found ${devices.length} camera(s)`, 'info');

                    if (devices.length === 0) {
                        addStatus('No cameras found!', 'error');
                        scanning = false;
                        return;
                    }

                    // Try to find back camera, otherwise use first available
                    let selectedCamera = devices[0];
                    for (let device of devices) {
                        if (device.label.toLowerCase().includes('back') ||
                            device.label.toLowerCase().includes('rear') ||
                            device.label.toLowerCase().includes('environment')) {
                            selectedCamera = device;
                            break;
                        }
                    }

                    console.log('Selected camera:', selectedCamera);
                    addStatus(`Using: ${selectedCamera.label || 'Camera'}`, 'info');

                    const config = {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    };

                    // Start with selected camera ID
                    await html5QrCode.start(
                        selectedCamera.id,
                        config,
                        (decodedText) => {
                            console.log('QR Code detected:', decodedText);
                            addStatus(`QR Code: ${decodedText}`, 'success');
                            @this.dispatch('qr-code-scanned', decodedText);
                        },
                        (errorMessage) => {
                            // Silent - this fires continuously while scanning
                        }
                    );

                    addStatus('Scanner started successfully!', 'success');
                    console.log('QR scanner started successfully');

                } catch (error) {
                    console.error('Error starting scanner:', error);
                    addStatus(`Error: ${error.message}`, 'error');
                    scanning = false;
                }
            }

            async function stopScanning() {
                console.log('Stopping QR scanner...');
                addStatus('Stopping scanner...', 'info');

                if (!scanning || !html5QrCode) {
                    return;
                }

                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                    html5QrCode = null;
                    scanning = false;
                    addStatus('Scanner stopped!', 'success');
                    console.log('QR scanner stopped successfully');
                } catch (error) {
                    console.error('Error stopping scanner:', error);
                    addStatus(`Stop error: ${error.message}`, 'error');
                }
            }
        </script>
    @endpush
</x-filament-panels::page>
