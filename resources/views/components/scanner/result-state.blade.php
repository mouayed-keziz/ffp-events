@props([
    'state' => 'empty', // empty, success, error
    'title' => '',
    'description' => '',
    'icon' => null,
    'errorMessage' => null,
    'successBlocks' => [],
])

<!-- Results Container with Border -->
<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
    <!-- Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                @if ($icon)
                    @php($iconComponent = $icon)
                    <x-dynamic-component :component="$iconComponent" class="h-6 w-6 text-gray-400 dark:text-gray-500" />
                @else
                    <x-heroicon-o-clipboard-document-list class="h-6 w-6 text-gray-400 dark:text-gray-500" />
                @endif
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ $title ?: __('panel/scanner.results_section_title') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{-- {{ $description ?: 'View detailed information about scanned badges' }} --}}
                </p>
            </div>
        </div>
    </div>

    <!-- Content with same aspect ratio as scanner -->
    <div class="p-6">
        <div class="aspect-video w-full">
            @if ($state === 'success' && !empty($successBlocks))
                <!-- Success State with Custom Blocks -->
                <div class="h-full flex flex-col space-y-4 overflow-y-auto">
                    <x-scanner.result-grid :blocks="$successBlocks" />
                </div>
            @elseif ($state === 'error')
                <!-- Error State -->
                <div class="h-full flex flex-col items-center justify-center text-center">
                    <x-heroicon-o-exclamation-triangle class="w-16 h-16 text-red-400 dark:text-red-500 mb-4" />
                    <h4 class="text-lg font-medium text-red-900 dark:text-red-300 mb-2">
                        {{ __('panel/scanner.scan_error') }}
                    </h4>
                    <p class="text-sm text-red-600 dark:text-red-400 max-w-xs">
                        {{ $errorMessage ?: __('panel/scanner.scan_error_description') }}
                    </p>
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
