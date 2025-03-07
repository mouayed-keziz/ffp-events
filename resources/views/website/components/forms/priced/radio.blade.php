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

    // Also create a selectedValue property for radio functionality
    data_set($this, 'formData.' . $answerPath . '.selectedValue', null);
}

// Find the selected value if any
$selectedValue = data_get($this, 'formData.' . $answerPath . '.selectedValue');
    @endphp

    <div class="space-y-3">
        @php
            $radioName = 'radio_' . str_replace('.', '_', $answerPath);
        @endphp

        @foreach ($data['options'] ?? [] as $optionIndex => $option)
            @php
                // Get the current option value
                $optionValue = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
                $isSelected = $selectedValue === $optionValue;

                // Find the corresponding option index in our answer structure for display
                $optionAnswerIndex = -1;
                foreach (data_get($this, 'formData.' . $answerPath . '.options', []) as $idx => $optionData) {
                    if ($optionData['value'] == $optionValue) {
                        $optionAnswerIndex = $idx;
                        break;
                    }
                }
            @endphp

            <div class="flex items-start">
                <div class="flex items-center flex-wrap gap-2">
                    <input type="radio" id="{{ $radioName }}_{{ $loop->index }}" class="radio radio-primary mr-2"
                        name="{{ $radioName }}" value="{{ $optionValue }}"
                        wire:model.live="formData.{{ $answerPath }}.selectedValue"
                        wire:change="updateRadioSelection('{{ $answerPath }}', $event.target.value)"
                        {{ $isSelected ? 'checked' : '' }}>

                    <label for="{{ $radioName }}_{{ $loop->index }}" class="cursor-pointer mr-2">
                        {{ $optionValue }}
                    </label>

                    @if (isset($option['price']))
                        @include('website.components.forms.priced.price-badge', [
                            'price' => $option['price'][$this->preferred_currency] ?? 0,
                            'currency' => $this->preferred_currency,
                        ])
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- Debug information to help troubleshoot --}}
    @include('website.components.forms.debug-path', [
        'answerPath' => $answerPath,
    ])
</div>
