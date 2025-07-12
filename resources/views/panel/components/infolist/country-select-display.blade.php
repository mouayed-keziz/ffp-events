@php
    use Filament\Support\Facades\FilamentView;
    use App\Constants\Countries;

    $locale = app()->getLocale();

    // Get state data or default to empty array
    $state = $getState() ?? [];

    // Handle both old and new formats
    $selectedCountryCode = null;
    $selectedCountryName = null;

    // New simplified format
    if (isset($state['selected_country_code'])) {
        $selectedCountryCode = $state['selected_country_code'];
        $test = __('countries.' . $selectedCountryCode);
        $selectedCountryName = $state['selected_country_name'];
    }
    // Legacy format support
    elseif (isset($state['selected_option'])) {
        $selectedOption = $state['selected_option'];
        $selectedCountryCode = $selectedOption['code'] ?? ($selectedOption['value'] ?? null);
        $selectedCountryName = $selectedOption['name'] ?? null;
    }
    // Even older legacy format
    elseif (isset($state['options']) && is_array($state['options'])) {
        foreach ($state['options'] as $option) {
            if (!empty($option['selected']) && $option['selected'] === true) {
                $selectedCountryCode = $option['code'] ?? ($option['value'] ?? null);
                $selectedCountryName = $option['name'] ?? null;
                break;
            }
        }
    }
@endphp

<div class="fi-country-select-options space-y-2">
    @if ($selectedCountryCode && $selectedCountryName)
        <!-- Display the selected country -->
        <div
            class="fi-resource-item flex items-center gap-3 rounded-lg border border-primary-600 bg-primary-600/10 p-3 dark:border-primary-500 dark:bg-primary-600/10">
            <!-- Country flag or icon -->
            <div class="flex-shrink-0">
                <span class="text-primary-500 dark:text-primary-400">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>

            <!-- Country Information -->
            <div class="flex-1">
                <div class="font-medium text-gray-900 dark:text-white">
                    {{ $test }}
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('panel/forms.exhibitors.country_select.country_code') }}: {{ $selectedCountryCode }}
                </div>
            </div>

            <!-- Globe icon to indicate it's a country -->
            <div class="flex-shrink-0">
                <span class="text-gray-400 dark:text-gray-500">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3s-4.5 4.03-4.5 9 2.015 9 4.5 9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2C8.37 2 5.5 5.686 5.5 10c0 1.657.343 3.23.963 4.61" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 2c3.63 0 6.5 3.686 6.5 8 0 1.657-.343 3.23-.963 4.61" />
                    </svg>
                </span>
            </div>
        </div>
    @else
        <!-- No country selected -->
        <div
            class="fi-resource-item flex items-center gap-3 rounded-lg border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
            <div class="flex-shrink-0">
                <span class="text-gray-400 dark:text-gray-500">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3s-4.5 4.03-4.5 9 2.015 9 4.5 9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20" />
                    </svg>
                </span>
            </div>
            <div class="flex-1">
                <span class="text-gray-500 dark:text-gray-400 italic">
                    {{ __('panel/forms.exhibitors.country_select.no_country_selected') }}
                </span>
            </div>
        </div>
    @endif
</div>
