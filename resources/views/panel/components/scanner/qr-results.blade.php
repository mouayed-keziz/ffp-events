<!-- Results Container with Border -->
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <x-heroicon-o-clipboard-document-list class="h-6 w-6 text-gray-400 dark:text-gray-500" />
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('panel/scanner.results_section_title') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    View detailed information about scanned badges
                </p>
            </div>
        </div>
    </div>

    <!-- Content with same aspect ratio as scanner -->
    <div class="p-6">
        <div class="aspect-video w-full">
            @if ($lastScannedCode)
                <!-- Results Display -->
                <div class="h-full flex flex-col space-y-4 overflow-y-auto">
                    <!-- Quick Info Card -->
                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex-shrink-0">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <x-heroicon-s-check-circle class="h-6 w-6 text-green-600 dark:text-green-400" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                    {{ __('panel/scanner.last_scanned_result') }}
                                </p>
                                <p class="text-sm text-green-700 dark:text-green-300 truncate">
                                    {{ $scannedAt }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Results -->
                    <div class="flex-1 space-y-3 overflow-y-auto">
                        <!-- Scan Data -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3">
                            <div class="flex items-start space-x-2">
                                <x-heroicon-o-identification
                                    class="h-4 w-4 text-gray-400 dark:text-gray-500 mt-0.5 flex-shrink-0" />
                                <div class="min-w-0 flex-1">
                                    <dt class="text-xs font-medium text-gray-900 dark:text-white mb-1">
                                        {{ __('panel/scanner.scan_data') }}
                                    </dt>
                                    <dd
                                        class="text-xs text-gray-600 dark:text-gray-300 font-mono break-all bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded px-2 py-1">
                                        {{ $lastScannedCode }}
                                    </dd>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-2 gap-3">
                            <!-- User -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <x-heroicon-o-user class="h-4 w-4 text-gray-400 dark:text-gray-500 flex-shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <dt class="text-xs font-medium text-gray-900 dark:text-white">
                                            {{ __('panel/scanner.user') }}
                                        </dt>
                                        <dd class="text-xs text-gray-600 dark:text-gray-300 truncate">
                                            {{ $scanUser }}
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Format -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3">
                                <div class="flex items-center space-x-2">
                                    <x-heroicon-o-tag class="h-4 w-4 text-gray-400 dark:text-gray-500 flex-shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <dt class="text-xs font-medium text-gray-900 dark:text-white">
                                            {{ __('panel/scanner.format') }}
                                        </dt>
                                        <dd class="text-xs text-gray-600 dark:text-gray-300">
                                            @if (filter_var($lastScannedCode, FILTER_VALIDATE_URL))
                                                <span
                                                    class="inline-flex items-center rounded-full bg-blue-100 dark:bg-blue-900/30 px-1.5 py-0.5 text-xs font-medium text-blue-700 dark:text-blue-300">
                                                    URL
                                                </span>
                                            @elseif(is_numeric($lastScannedCode))
                                                <span
                                                    class="inline-flex items-center rounded-full bg-purple-100 dark:bg-purple-900/30 px-1.5 py-0.5 text-xs font-medium text-purple-700 dark:text-purple-300">
                                                    Numeric
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    Text
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </div>
                            </div>

                            <!-- Length -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-3 col-span-2">
                                <div class="flex items-center space-x-2">
                                    <x-heroicon-o-calculator
                                        class="h-4 w-4 text-gray-400 dark:text-gray-500 flex-shrink-0" />
                                    <div class="min-w-0 flex-1">
                                        <dt class="text-xs font-medium text-gray-900 dark:text-white">
                                            {{ __('panel/scanner.length') }}
                                        </dt>
                                        <dd class="text-xs text-gray-600 dark:text-gray-300">
                                            {{ strlen($lastScannedCode) }} characters
                                        </dd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="h-full flex flex-col items-center justify-center text-center">
                    <x-heroicon-o-inbox class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        {{ __('panel/scanner.no_results') }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs">
                        {{ __('panel/scanner.scan_another') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
