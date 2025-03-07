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
    @endphp

    <div class="space-y-3">
        @foreach ($data['options'] ?? [] as $optionIndex => $option)
            @php
                // Find the corresponding option in our answer structure
                $optionAnswerIndex = -1;
                $isSelected = false;

                foreach (data_get($this, 'formData.' . $answerPath . '.options', []) as $idx => $optionData) {
                    $currentValue = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
                    if ($optionData['value'] == $currentValue) {
                        $optionAnswerIndex = $idx;
                        $isSelected = !empty($optionData['selected']) && $optionData['selected'] === true;
                        break;
                    }
                }
            @endphp

            <div class="flex items-start">
                <div class="flex items-center flex-wrap gap-2">
                    <input type="checkbox" id="{{ $answerPath }}_{{ $loop->index }}"
                        class="checkbox checkbox-primary rounded-md mr-2"
                        wire:model.live="formData.{{ $answerPath }}.options.{{ $optionAnswerIndex }}.selected"
                        {{ $isSelected ? 'checked' : '' }}>

                    <label for="{{ $answerPath }}_{{ $loop->index }}" class="cursor-pointer mr-2">
                        {{ $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '') }}
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
</div>
