@php
    use Filament\Support\Facades\FilamentView;

    $locale = app()->getLocale();

    // Get state data or default to empty array
    $state = $getState() ?? [];
    $options = $state['options'] ?? [];

    // Map of option values to selected status
    $selectedMap = collect($state['selected_options'] ?? [])
        ->pluck('value')
        ->toArray();
@endphp

<div class="fi-checkbox-options space-y-2">
    @if (!empty($options))
        <ul class="space-y-2">
            @foreach ($options as $option)
                @php
                    $optionText = $option['option'][$locale] ?? ($option['option']['en'] ?? '');
                    $isSelected = in_array($option['value'], $selectedMap);
                @endphp
                <li
                    class="fi-resource-item flex items-center gap-2 rounded-lg border p-2
                        {{ $isSelected
                            ? 'border-primary-600 bg-primary-600/10 dark:border-primary-500 dark:bg-primary-600/10'
                            : 'border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800' }}">

                    <!-- Checkbox icon -->
                    <div class="flex-shrink-0">
                        @if ($isSelected)
                            <span class="text-primary-500 dark:text-primary-400">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </span>
                        @else
                            <span class="text-gray-400 dark:text-gray-500">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <rect width="18" height="18" x="3" y="3" rx="2" stroke-width="2" />
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
