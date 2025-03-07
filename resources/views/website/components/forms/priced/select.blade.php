@props(['data', 'answerPath'])

<div class="mb-4">
    <label class="label">
        <span class="label-text font-medium {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? ($data['label']['fr'] ?? '') }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span class="label-text-alt text-xs text-gray-500">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    @php
        // Initialize options array in answer structure if it doesn't exist
$optionsData = data_get($this, 'formData.' . $answerPath . '.options', []);
if (empty($optionsData)) {
    // Initialize the answer with all available options
    $optionsData = collect($data['options'] ?? [])
        ->map(function ($option) {
            return [
                'option' => $option['option'] ?? [],
                'price' => $option['price'] ?? [],
                'selected' => false,
                'value' => $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? ''),
            ];
        })
        ->toArray();

    // Set the initial options data in the model
    data_set($this, 'formData.' . $answerPath . '.options', $optionsData);
}

// Find the currently selected option
$selectedOptionLabel = '';
$selectedOptionPrice = null;

foreach ($optionsData as $optionData) {
    if (!empty($optionData['selected']) && $optionData['selected'] === true) {
        $selectedOptionLabel = $optionData['value'] ?? '';
        $selectedOptionPrice = $optionData['price'][$this->preferred_currency] ?? null;
                break;
            }
        }
    @endphp

    <div wire:key="select-{{ $answerPath }}" x-data="{
        open: false,
        selectedLabel: '{{ $selectedOptionLabel }}'
    }"
        @selected-changed.window="event => {
            if(event.detail && event.detail.path === '{{ $answerPath }}') {
                selectedLabel = event.detail.label;
            }
        }"
        class="relative">
        <button type="button" @click="open = !open"
            class="input input-bordered bg-white rounded-md w-full flex justify-between items-center"
            :class="selectedLabel ? '' : 'text-gray-500'">
            <div class="flex items-center gap-2 overflow-hidden">
                <span x-text="selectedLabel || '{{ __('Select an option') }}'" class="truncate"></span>

                @if ($selectedOptionLabel)
                    <span class="bg-primary/10 text-primary font-semibold text-sm px-3 py-1 rounded-md">
                        {{ $selectedOptionPrice ?? 0 }} {{ $this->preferred_currency }}
                    </span>
                @endif
            </div>
            <svg class="h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <div x-show="open" @click.away="open = false" x-transition
            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 overflow-auto focus:outline-none"
            style="display: none;">
            @foreach ($data['options'] ?? [] as $option)
                @php
                    $optionLabel = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');

                    // Find if this option is selected
                    $isSelected = false;
                    foreach ($optionsData as $optionData) {
                        if (
                            $optionData['value'] == $optionLabel &&
                            !empty($optionData['selected']) &&
                            $optionData['selected'] === true
                        ) {
                            $isSelected = true;
                            break;
                        }
                    }

                    $optionPrice = $option['price'][$this->preferred_currency] ?? 0;
                @endphp

                <button type="button"
                    @click="selectedLabel = '{{ $optionLabel }}'; open = false; $wire.call('updateSelectOption', '{{ $answerPath }}', '{{ $optionLabel }}');"
                    class="w-full text-left px-4 py-2 hover:bg-gray-100 {{ $isSelected ? 'bg-primary/10' : '' }}">
                    <div class="flex items-center gap-2">
                        <span>{{ $optionLabel }}</span>
                        @if (isset($option['price']))
                            @include('website.components.forms.priced.price-badge', [
                                'price' => $optionPrice,
                                'currency' => $this->preferred_currency,
                            ])
                        @endif
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Debug information to help troubleshoot --}}
    @if (config('app.debug'))
        <div class="mt-2 p-2 bg-gray-100 text-xs rounded">
            <p>Debug - Answer Path: {{ $answerPath }}</p>
            <pre>@json(data_get($this, 'formData.' . $answerPath), JSON_PRETTY_PRINT)</pre>
        </div>
    @endif
</div>
