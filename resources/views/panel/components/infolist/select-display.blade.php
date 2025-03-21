@php
    use Filament\Support\Facades\FilamentView;

    $locale = app()->getLocale();

    // Get state data or default to empty array
    $state = $getState() ?? [];
    $options = $state['options'] ?? [];

    // Get the selected option
    $selectedOption = $state['selected_option'] ?? null;
    $selectedValue = $selectedOption ? $selectedOption['value'] : null;
@endphp

<div class="fi-select-options space-y-2">
    @if (!empty($options))
        <ul class="space-y-2">
            @foreach ($options as $option)
                @php
                    $optionText = $option['option'][$locale] ?? ($option['option']['en'] ?? '');
                    $isSelected = $option['value'] === $selectedValue;
                @endphp
                <li
                    class="fi-resource-item flex items-center gap-2 rounded-lg border p-2
                        {{ $isSelected
                            ? 'border-success-600 bg-success-50 dark:border-success-500 dark:bg-success-950/50'
                            : 'border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800' }}">

                    <!-- Select icon/indicator -->
                    <div class="flex-shrink-0">
                        @if ($isSelected)
                            <span class="text-success-500 dark:text-success-400">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"
                                        fill="none" />
                                </svg>
                            </span>
                        @endif
                    </div>

                    <!-- Option Text -->
                    <div class="flex-1">
                        <span
                            class="{{ $isSelected ? 'font-medium text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
                            {{ $optionText }}
                        </span>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-gray-500 dark:text-gray-400 italic">{{ __('No options available') }}</div>
    @endif
</div>
