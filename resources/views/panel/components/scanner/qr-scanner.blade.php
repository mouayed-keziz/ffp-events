<!-- Scanner Container with Border -->
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-3 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="flex-shrink-0">
                    <x-heroicon-o-identification class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400 dark:text-gray-500" />
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('panel/scanner.scanner_section_title') }}
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        {{-- {{ __('panel/scanner.scanner_description') }} --}}
                    </p>
                </div>
            </div>

            <!-- Action Toggle -->
            <div class="flex items-center gap-1 sm:gap-2">
                <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 hidden sm:inline">
                    {{ __('panel/scanner.current_action') }}:
                </span>
                <button type="button" id="actionToggle"
                    class="fi-btn fi-btn-size-sm inline-flex items-center gap-x-1 sm:gap-x-2 rounded-lg px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm font-semibold shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 transition-colors text-white bg-blue-600 hover:bg-blue-500 focus-visible:outline-blue-600 dark:bg-blue-500 dark:hover:bg-blue-400">
                    <x-heroicon-m-check-circle class="h-3 w-3 sm:h-4 sm:w-4" />
                    <span id="actionLabel">Check In</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Camera Selection Section -->
    <div id="cameraSelection"
        class="hidden border-b border-gray-200 dark:border-gray-700 px-3 sm:px-6 py-2 sm:py-3 bg-gray-50 dark:bg-gray-800/50">
        <div class="flex items-center justify-between gap-2 sm:gap-3">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-camera class="h-4 w-4 text-gray-400 dark:text-gray-500" />
                    <label for="cameraSelect" class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">
                        Camera:
                    </label>
                </div>
                <select id="cameraSelect"
                    class="flex-1 max-w-xs text-xs sm:text-sm rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-2 py-1.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Camera</option>
                </select>
            </div>

            <div class="flex items-center">
                <!-- Mirror Toggle Button (icon only) -->
                <button type="button" id="mirrorToggle" aria-label="Toggle mirror"
                    class="fi-btn fi-btn-size-sm inline-flex items-center rounded-lg px-2 py-1.5 text-xs font-semibold shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <x-heroicon-o-arrows-right-left class="h-4 w-4" />
                </button>
            </div>
        </div>
    </div>

    <!-- Scanner Container with taller aspect ratio on mobile -->
    <div class="p-3 sm:p-6">
        <div class="aspect-square sm:aspect-video w-full">
            <div class="relative w-full h-full">
                <div id="qr-reader" class="w-full h-full rounded-lg overflow-hidden"></div>
                <div id="placeholder"
                    class="absolute inset-0 rounded-lg flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-800">
                    <x-heroicon-o-identification
                        class="w-16 h-16 sm:w-20 sm:h-20 text-gray- 300 dark:text-gray-600 mb-3 sm:mb-4" />
                    <p class="text-sm sm:text-lg font-medium text-gray-900 dark:text-white mb-1 sm:mb-2">
                        {{ __('panel/scanner.scanner_placeholder') }}
                    </p>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center max-w-xs px-2">
                        {{ __('panel/scanner.scanner_hint') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Default: not mirrored */
        #qr-reader video {
            transform: scaleX(1);
        }

        /* Mirrored when the container has .mirrored */
        #qr-reader.mirrored video {
            transform: scaleX(-1);
        }
    </style>
@endpush
@push('scripts')
    <script src="/js/html5-qrcode.min.js"></script>

    <script>
        let html5QrcodeScanner = null;
        let scanning = false;
        let currentAction = 'check_in'; // check_in or check_out
        let lastScannedCode = null; // Track last scanned code
        let scanCooldown = false; // Prevent rapid scanning
        const SCAN_COOLDOWN_MS = 3000; // 3 seconds between scans of the same code
        let availableCameras = []; // Store available cameras
        let selectedCameraId = null; // Currently selected camera

        // Get CSRF token and locale for API calls
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const currentLocale = document.documentElement.lang || 'en';

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

        function updateActionButton() {
            const actionToggle = document.getElementById('actionToggle');
            const actionLabel = document.getElementById('actionLabel');

            if (actionToggle && actionLabel) {
                const isCheckIn = currentAction === 'check_in';

                // Update button appearance
                actionToggle.className = `fi-btn fi-btn-size-sm inline-flex items-center gap-x-1 sm:gap-x-2 rounded-lg px-2 sm:px-3 py-1 sm:py-1.5 text-xs sm:text-sm font-semibold shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 transition-colors text-white ${
                    isCheckIn 
                        ? 'bg-blue-600 hover:bg-blue-500 focus-visible:outline-blue-600 dark:bg-blue-500 dark:hover:bg-blue-400'
                        : 'bg-orange-600 hover:bg-orange-500 focus-visible:outline-orange-600 dark:bg-orange-500 dark:hover:bg-orange-400'
                }`;

                // Update icon and label
                const icon = actionToggle.querySelector('svg');
                if (icon) {
                    icon.outerHTML = isCheckIn ?
                        '<svg class="h-3 w-3 sm:h-4 sm:w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.73 10.146a.75.75 0 00-1.06 1.061l2.03 2.03a.75.75 0 001.137-.089l3.857-5.401z" clip-rule="evenodd"></path></svg>' :
                        '<svg class="h-3 w-3 sm:h-4 sm:w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"></path></svg>';
                }

                actionLabel.textContent = isCheckIn ? 'Check In' : 'Check Out';
            }
        }

        // Get available cameras and populate camera selection dropdown
        async function initializeCameras() {
            try {
                // Check if Html5Qrcode is available, if not try to wait for it
                if (typeof Html5Qrcode === 'undefined') {
                    console.log('Html5Qrcode not available, trying to access from window...');

                    // Try to get it from window object
                    if (window.Html5Qrcode) {
                        window.Html5Qrcode = window.Html5Qrcode;
                    } else {
                        console.log('Html5Qrcode not found, will try again when starting scanner');
                        return;
                    }
                }

                const devices = await Html5Qrcode.getCameras();
                availableCameras = devices;

                const cameraSelect = document.getElementById('cameraSelect');
                const cameraSelection = document.getElementById('cameraSelection');

                if (!cameraSelect || !cameraSelection) return;

                // Clear existing options
                cameraSelect.innerHTML = '<option value="">Select Camera</option>';

                if (devices && devices.length > 0) {
                    // Show camera selection if we have cameras
                    cameraSelection.classList.remove('hidden');

                    devices.forEach((device, index) => {
                        const option = document.createElement('option');
                        option.value = device.id;
                        option.textContent = device.label || `Camera ${index + 1}`;
                        cameraSelect.appendChild(option);
                    });

                    // Set default camera (prefer back camera on mobile)
                    const backCamera = devices.find(device =>
                        device.label && device.label.toLowerCase().includes('back')
                    );

                    if (backCamera) {
                        selectedCameraId = backCamera.id;
                        cameraSelect.value = backCamera.id;
                    } else if (devices.length > 0) {
                        selectedCameraId = devices[0].id;
                        cameraSelect.value = devices[0].id;
                    }

                    console.log('Available cameras:', devices);
                    console.log('Selected camera:', selectedCameraId);
                } else {
                    cameraSelection.classList.add('hidden');
                    console.log('No cameras found');
                }

            } catch (error) {
                console.error('Error getting cameras:', error);
                const cameraSelection = document.getElementById('cameraSelection');
                if (cameraSelection) {
                    cameraSelection.classList.add('hidden');
                }

                // Try to get cameras permissions and retry
                try {
                    await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    setTimeout(() => initializeCameras(), 1000);
                } catch (permissionError) {
                    console.error('Camera permission denied:', permissionError);
                }
            }
        }

        // Handle camera selection change
        function onCameraChange() {
            const cameraSelect = document.getElementById('cameraSelect');
            if (cameraSelect) {
                selectedCameraId = cameraSelect.value;
                console.log('Camera changed to:', selectedCameraId);

                // If scanner is running, restart it with new camera
                if (scanning && html5QrcodeScanner) {
                    stopScanner();
                    setTimeout(() => {
                        startScanner();
                    }, 100);
                }
            }
        }

        function toggleAction() {
            currentAction = currentAction === 'check_in' ? 'check_out' : 'check_in';
            updateActionButton();
        }

        async function processScanResult(qrData) {
            // Prevent rapid scanning of the same code
            if (scanCooldown || lastScannedCode === qrData) {
                console.log('Scan cooldown active or duplicate scan, ignoring:', qrData);
                return;
            }

            // Set cooldown and track last scanned code
            lastScannedCode = qrData;
            scanCooldown = true;

            setTimeout(() => {
                scanCooldown = false;
                lastScannedCode = null;
            }, SCAN_COOLDOWN_MS);

            try {
                console.log('Processing scan result:', qrData);

                const response = await fetch('/admin/qr-scanner/process-scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        qr_data: qrData,
                        action: currentAction,
                        locale: currentLocale
                    })
                });

                const statusCode = response.status;
                const result = await response.json();

                console.log('Status Code:', statusCode);
                console.log('Response JSON:', result);

                if (result.success) {
                    console.log('Scan processed successfully:', result);
                    updateResultsDisplay(result.data);
                } else {
                    console.error('Scan processing failed:', result);
                    updateResultsDisplay({
                        state: 'error',
                        error_message: result.message || 'Failed to process scan',
                        result_blocks: []
                    });
                }
            } catch (error) {
                console.error('Error processing scan:', error);
                updateResultsDisplay({
                    state: 'error',
                    error_message: 'Network error occurred while processing scan',
                    result_blocks: []
                });
            }
        }

        function updateResultsDisplay(data) {
            // Find the result container by ID
            const resultContainer = document.getElementById('scan-results-container');

            if (!resultContainer) {
                console.error('Could not find result container with ID: scan-results-container');
                return;
            }

            console.log('Found result container, updating with data:', data);

            // Build the new result HTML
            let resultHTML = '';

            if (data.state === 'success' && data.result_blocks && data.result_blocks.length > 0) {
                resultHTML = buildSuccessResultHTML(data.result_blocks);
            } else if (data.state === 'error') {
                resultHTML = buildErrorResultHTML(data.error_message);
            } else {
                resultHTML = buildEmptyResultHTML();
            }

            // Update the container
            resultContainer.innerHTML = resultHTML;
        }

        function buildSuccessResultHTML(blocks) {
            const fullBlocks = blocks.filter(block => block.layout === 'full');
            const gridBlocks = blocks.filter(block => block.layout === 'grid');

            let html = `
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-3 sm:px-6 py-3 sm:py-4">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">Scan Results</h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-6">
                        <div class="aspect-square sm:aspect-video w-full">
                            <div class="h-full flex flex-col space-y-2 sm:space-y-4 overflow-y-auto">
            `;

            // Add full-width blocks
            fullBlocks.forEach(block => {
                html += buildBlockHTML(block, true);
            });

            // Add grid blocks
            if (gridBlocks.length > 0) {
                html += '<div class="grid grid-cols-2 gap-2 sm:gap-3">';
                gridBlocks.forEach(block => {
                    html += buildBlockHTML(block, false);
                });
                html += '</div>';
            }

            html += `
                            </div>
                        </div>
                    </div>
                </div>
            `;

            return html;
        }

        function buildBlockHTML(block, isFullWidth) {
            const styleClasses = {
                'highlight': 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4',
                'success': 'bg-green-50 dark:bg-green-800/50',
                'warning': 'bg-yellow-50 dark:bg-yellow-800/50',
                'danger': 'bg-red-50 dark:bg-red-800/50',
                'info': 'bg-blue-50 dark:bg-blue-800/50',
                'default': 'bg-gray-50 dark:bg-gray-800/50'
            };

            const iconColorClasses = {
                'highlight': 'text-green-600 dark:text-green-400',
                'success': 'text-green-600 dark:text-green-400',
                'warning': 'text-yellow-600 dark:text-yellow-400',
                'danger': 'text-red-600 dark:text-red-400',
                'info': 'text-blue-600 dark:text-blue-400',
                'default': 'text-gray-400 dark:text-gray-500'
            };

            const labelColorClasses = {
                'highlight': 'text-green-800 dark:text-green-200',
                'success': 'text-green-900 dark:text-green-300',
                'warning': 'text-yellow-900 dark:text-yellow-300',
                'danger': 'text-red-900 dark:text-red-300',
                'info': 'text-blue-900 dark:text-blue-300',
                'default': 'text-gray-900 dark:text-white'
            };

            const dataColorClasses = {
                'highlight': 'text-green-700 dark:text-green-300',
                'success': 'text-green-700 dark:text-green-300',
                'warning': 'text-yellow-700 dark:text-yellow-300',
                'danger': 'text-red-700 dark:text-red-300',
                'info': 'text-blue-700 dark:text-blue-300',
                'default': 'text-gray-600 dark:text-gray-300'
            };

            const widthClass = isFullWidth ? 'w-full' : '';
            const colSpanClass = (block.colSpan === 2) ? 'col-span-2' : '';
            const baseClasses = 'rounded-lg p-2 sm:p-3';
            const styleClass = styleClasses[block.style] || styleClasses['default'];

            const iconClass = iconColorClasses[block.style] || iconColorClasses['default'];
            const labelClass = labelColorClasses[block.style] || labelColorClasses['default'];
            const dataClass = dataColorClasses[block.style] || dataColorClasses['default'];

            // Check if this is an action block
            if (block.type === 'action') {
                return `
                    <div class="${baseClasses} ${styleClass} ${widthClass} ${colSpanClass}">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="flex-shrink-0">
                                ${getIconSvg(block.icon, iconClass)}
                            </div>
                            <div class="min-w-0 flex-1">
                                <button 
                                    onclick="handleAction('${block.action}', '${block.data}')"
                                    class="fi-btn fi-btn-size-sm inline-flex items-center gap-x-2 rounded-lg bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                    ${getIconSvg(block.icon, 'text-white', 'h-4 w-4')}
                                    ${block.label}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }

            return `
                <div class="${baseClasses} ${styleClass} ${colSpanClass}">
                    <div class="${block.style === 'highlight' ? 'flex items-center space-x-2 sm:space-x-3' : 'flex items-start space-x-1 sm:space-x-2'}">
                        <div class="flex-shrink-0">
                            <svg class="${block.style === 'highlight' ? 'h-5 w-5 sm:h-6 sm:w-6' : 'h-3 w-3 sm:h-4 sm:w-4 mt-0.5'} ${iconClass}" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.73 10.146a.75.75 0 00-1.06 1.061l2.03 2.03a.75.75 0 001.137-.089l3.857-5.401z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="${block.style === 'highlight' ? 'text-xs sm:text-sm font-medium' : 'text-xs font-medium'} ${labelClass} ${block.style === 'highlight' ? 'mb-0' : 'mb-1'}">
                                ${block.label}
                            </p>
                            <div class="${block.style === 'highlight' ? 'text-xs sm:text-sm' : 'text-xs'} ${dataClass} ${block.style === 'highlight' ? 'truncate' : ''}">
                                ${block.action ? `<a href="#" onclick="handleAction('${block.action}', '${block.data}'); return false;" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">${block.data}</a>` : block.data}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function getIconSvg(iconName, colorClass = '', sizeClass = 'h-4 w-4') {
            if (!iconName) return '';

            const iconMap = {
                'heroicon-s-check-circle': '<svg class="' + sizeClass + ' ' + colorClass +
                    '" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.73 10.146a.75.75 0 00-1.06 1.061l2.03 2.03a.75.75 0 001.137-.089l3.857-5.401z" clip-rule="evenodd"></path></svg>',
                'heroicon-o-arrow-down-tray': '<svg class="' + sizeClass + ' ' + colorClass +
                    '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>',
                'heroicon-o-qr-code': '<svg class="' + sizeClass + ' ' + colorClass +
                    '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"></path></svg>',
                'heroicon-o-user': '<svg class="' + sizeClass + ' ' + colorClass +
                    '" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>'
            };

            return iconMap[iconName] || '';
        }

        // Handle action button clicks
        async function handleAction(actionType, actionData) {
            if (actionType === 'download-badge') {
                await downloadBadge(actionData);
            }
        }

        // Download badge function
        async function downloadBadge(badgeCode) {
            try {
                console.log('Downloading badge for code:', badgeCode);

                const response = await fetch('/admin/qr-scanner/download-badge', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        badge_code: badgeCode
                    })
                });

                if (response.ok) {
                    // Check if the response is JSON (error) or binary (file)
                    const contentType = response.headers.get('Content-Type');

                    if (contentType && contentType.includes('application/json')) {
                        // Handle JSON error response
                        const result = await response.json();
                        console.error('Badge download failed:', result);
                        alert('Failed to download badge: ' + (result.message || 'Unknown error'));
                    } else {
                        // Handle binary file response
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = 'badge_' + badgeCode + '.png';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                        console.log('Badge downloaded successfully');
                    }
                } else {
                    // Handle HTTP error
                    try {
                        const result = await response.json();
                        console.error('Badge download failed:', result);
                        alert('Failed to download badge: ' + (result.message || 'Unknown error'));
                    } catch (e) {
                        console.error('Badge download failed with status:', response.status);
                        alert('Failed to download badge. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error downloading badge:', error);
                alert('Network error occurred while downloading badge');
            }
        }

        function buildErrorResultHTML(errorMessage) {
            return `
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-3 sm:px-6 py-3 sm:py-4">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">Scan Results</h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-6">
                        <div class="aspect-square sm:aspect-video w-full">
                            <div class="h-full flex flex-col items-center justify-center text-center">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-400 dark:text-red-500 mb-3 sm:mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                <h4 class="text-sm sm:text-lg font-medium text-red-900 dark:text-red-300 mb-1 sm:mb-2">Scan Error</h4>
                                <p class="text-xs sm:text-sm text-red-600 dark:text-red-400 max-w-xs px-2">${errorMessage || 'An error occurred while processing the badge'}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function buildEmptyResultHTML() {
            return `
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                    <div class="border-b border-gray-200 dark:border-gray-700 px-3 sm:px-6 py-3 sm:py-4">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">Scan Results</h3>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 sm:p-6">
                        <div class="aspect-square sm:aspect-video w-full">
                            <div class="h-full flex flex-col items-center justify-center text-center">
                                <svg class="w-12 h-12 sm:w-16 sm:h-16 text-gray-300 dark:text-gray-600 mb-3 sm:mb-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M1 11.27c0-.246.033-.492.099-.73l1.523-5.521A2.75 2.75 0 015.273 3h9.454a2.75 2.75 0 012.651 2.019l1.523 5.52c.066.239.099.485.099.731V15a2 2 0 01-2 2H3a2 2 0 01-2-2v-3.73zm3.068-5.852A.75.75 0 014.818 5h10.364a.75.75 0 01.75.418l1.452 5.265c.015.055-.006.101-.04.101H2.656c-.034 0-.055-.046-.04-.101L4.068 5.418z" clip-rule="evenodd"></path>
                                </svg>
                                <h4 class="text-sm sm:text-lg font-medium text-gray-900 dark:text-white mb-1 sm:mb-2">No badges scanned yet</h4>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 max-w-xs px-2">Start scanning to see badge information here</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
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

            // Initialize cameras if not done already
            if (availableCameras.length === 0) {
                initializeCameras();
            }

            scanning = true;
            updateUI();

            // Success callback - called when QR code is scanned
            function onScanSuccess(decodedText, decodedResult) {
                console.log(`QR Code scanned: ${decodedText}`, decodedResult);

                // Process scan via API instead of Livewire
                processScanResult(decodedText);
            }

            // Error callback - called when scan fails (optional)
            function onScanError(errorMessage) {
                // This fires continuously while scanning, so we don't log it
                if (!errorMessage.includes('No QR code found')) {
                    console.log('Scan error:', errorMessage);
                }
            }

            try {
                const isMobile = window.innerWidth < 640;

                // Scanner configuration
                const config = {
                    fps: 5,
                    qrbox: 150,
                    aspectRatio: isMobile ? 1 : (16 / 9),
                    rememberLastUsedCamera: true,
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
                };

                // Add camera constraint if a specific camera is selected
                if (selectedCameraId) {
                    config.videoConstraints = {
                        deviceId: {
                            exact: selectedCameraId
                        }
                    };
                    console.log('Using selected camera:', selectedCameraId);
                } else {
                    console.log('Using default camera selection');
                }

                html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", config);

                // Render the scanner
                html5QrcodeScanner.render(onScanSuccess, onScanError);

                console.log('QR scanner started successfully with camera:', selectedCameraId || 'default');

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

        // Robust initialization (Filament/Livewire pages may load after DOMContentLoaded has fired)
        function initScannerUI() {
            // Prevent double init
            if (window.__qrScannerInitialized) return;
            window.__qrScannerInitialized = true;

            updateUI();
            updateActionButton();

            // Initialize mirror state (default to mirrored to match previous behavior)
            const qrReaderEl = document.getElementById('qr-reader');
            if (qrReaderEl && !qrReaderEl.classList.contains('mirrored')) {
                qrReaderEl.classList.add('mirrored');
            }

            const actionToggle = document.getElementById('actionToggle');
            if (actionToggle && !actionToggle.dataset.listenerAttached) {
                actionToggle.addEventListener('click', toggleAction);
                actionToggle.dataset.listenerAttached = 'true';
            }

            const cameraSelect = document.getElementById('cameraSelect');
            if (cameraSelect && !cameraSelect.dataset.listenerAttached) {
                cameraSelect.addEventListener('change', onCameraChange);
                cameraSelect.dataset.listenerAttached = 'true';
            }

            const mirrorToggle = document.getElementById('mirrorToggle');
            if (mirrorToggle && !mirrorToggle.dataset.listenerAttached) {
                mirrorToggle.addEventListener('click', () => {
                    const el = document.getElementById('qr-reader');
                    if (!el) return;
                    el.classList.toggle('mirrored');
                });
                mirrorToggle.dataset.listenerAttached = 'true';
            }

            setTimeout(() => initializeCameras(), 500);
        }

        // Initialize immediately if DOM is already ready, else wait
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initScannerUI);
        } else {
            initScannerUI();
        }

        // Also hook into Livewire navigation (if Livewire is present) to re-init after page changes
        if (window.Livewire) {
            document.addEventListener('livewire:navigated', () => {
                window.__qrScannerInitialized = false; // allow re-init after navigation
                initScannerUI();
            });
        }
    </script>
@endpush
