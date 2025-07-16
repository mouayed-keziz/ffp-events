<!-- Scanner Container with Border -->
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <x-heroicon-o-identification class="h-6 w-6 text-gray-400 dark:text-gray-500" />
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('panel/scanner.scanner_section_title') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('panel/scanner.scanner_description') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Scanner Container with same aspect ratio as results -->
    <div class="p-6">
        <div class="aspect-video w-full">
            <div class="relative w-full h-full">
                <div id="qr-reader" class="w-full h-full rounded-lg overflow-hidden"></div>
                <div id="placeholder"
                    class="absolute inset-0 rounded-lg flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800">
                    <x-heroicon-o-identification class="w-20 h-20 text-gray-300 dark:text-gray-600 mb-4" />
                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        {{ __('panel/scanner.scanner_placeholder') }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center max-w-xs">
                        Use the "Start Scanner" button to begin scanning badges
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <!-- Include the HTML5 QR Code library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrcodeScanner = null;
        let scanning = false;

        function updateUI() {
            const startBtn = document.getElementById('startBtn');
            const stopBtn = document.getElementById('stopBtn');
            const placeholder = document.getElementById('placeholder');

            if (scanning) {
                if (startBtn) startBtn.style.display = 'none';
                if (stopBtn) stopBtn.style.display = 'inline-flex';
                if (placeholder) placeholder.style.display = 'none';
            } else {
                if (startBtn) startBtn.style.display = 'inline-flex';
                if (stopBtn) stopBtn.style.display = 'none';
                if (placeholder) placeholder.style.display = 'flex';
            }
        }

        function startScanner() {
            console.log('Starting QR scanner...');

            if (typeof Html5QrcodeScanner === 'undefined') {
                console.error('Html5QrcodeScanner library not loaded!');
                return;
            }

            if (scanning) {
                console.log('Scanner is already running!');
                return;
            }

            scanning = true;
            updateUI();

            // Success callback - called when QR code is scanned
            function onScanSuccess(decodedText, decodedResult) {
                console.log(`QR Code scanned: ${decodedText}`, decodedResult);

                // Trigger Livewire event
                @this.dispatch('qr-code-scanned', {
                    qrData: decodedText
                });
            }

            // Error callback - called when scan fails (optional)
            function onScanError(errorMessage) {
                // This fires continuously while scanning, so we don't log it
                if (!errorMessage.includes('No QR code found')) {
                    console.log('Scan error:', errorMessage);
                }
            }

            try {
                // Create scanner instance with square aspect ratio and larger QR box
                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", {
                        fps: 10,
                        // qrbox: 150, // Larger square QR detection box
                        // aspect video
                        aspectRatio: 16 / 9, // Wider aspect ratio for camera
                        rememberLastUsedCamera: true,
                        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
                    }
                );

                // Render the scanner
                html5QrcodeScanner.render(onScanSuccess, onScanError);

                console.log('QR scanner started successfully');

            } catch (error) {
                console.error('Error starting scanner:', error);
                scanning = false;
                updateUI();
            }
        }

        function stopScanner() {
            console.log('Stopping QR scanner...');

            if (!scanning || !html5QrcodeScanner) {
                console.log('No scanner to stop!');
                return;
            }

            try {
                // Clear the scanner
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                scanning = false;
                updateUI();

                console.log('QR scanner stopped successfully');

            } catch (error) {
                console.error('Error stopping scanner:', error);
            }
        }

        // Initialize UI on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateUI();
        });
    </script>
@endpush
