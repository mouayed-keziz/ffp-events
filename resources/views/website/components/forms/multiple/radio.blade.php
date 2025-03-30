@props(['data', 'answerPath', 'disabled' => false])
<div class="form-control my-4">
    <label class="label">
        <span class="label-text">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>
    @if ($data['description'][app()->getLocale()] ?? false)
        <small class="mb-2">{{ $data['description'][app()->getLocale()] }}</small>
    @endif

    @php
        // Initialize options array in answer structure if it doesn't exist
$optionsData = data_get($this, 'formData.' . $answerPath . '.options', []);
if (empty($optionsData)) {
    // Initialize the answer with all available options
    $optionsData = collect($data['options'] ?? [])
        ->map(function ($option) {
            return [
                'option' => $option['option'] ?? [],
                'selected' => false,
                'value' => $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? ''),
            ];
        })
        ->toArray();

    // Set the initial options data in the model
    data_set($this, 'formData.' . $answerPath . '.options', $optionsData);

    // Also create a selectedValue property for radio functionality
    data_set($this, 'formData.' . $answerPath . '.selectedValue', null);
} else {
    // Check if we need to initialize selectedValue from existing selected option
    $selectedOption = collect($optionsData)->firstWhere('selected', true);
    if ($selectedOption && !data_get($this, 'formData.' . $answerPath . '.selectedValue')) {
        // Set selectedValue based on the selected option's value
        data_set($this, 'formData.' . $answerPath . '.selectedValue', $selectedOption['value']);
    }
}

// Find the selected value if any
$selectedValue = data_get($this, 'formData.' . $answerPath . '.selectedValue');

    @endphp

    <div class="flex flex-col gap-2 {{ $disabled ? 'opacity-60' : '' }}" x-data="{ selected: @entangle('formData.' . $answerPath . '.selectedValue') }">
        @php
            $radioName = 'radio_' . str_replace('.', '_', $answerPath);
        @endphp

        @foreach ($data['options'] as $option)
            @php
                $optionLabel = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
            @endphp

            <label class="cursor-pointer flex items-center">
                <input type="radio" name="{{ $radioName }}" value="{{ $optionLabel }}"
                    wire:model.live="formData.{{ $answerPath }}.selectedValue" x-model="selected"
                    wire:change="updateRadioSelection('{{ $answerPath }}', $event.target.value)"
                    :class="{ 'radio-primary': selected === '{{ $optionLabel }}' }" class="radio mx-2 {{ $disabled ? 'cursor-not-allowed' : '' }}"
                    @if ($data['required'] ?? false) required @endif {{ $disabled ? 'disabled' : '' }}>
                <span>{{ $optionLabel }}</span>
            </label>
        @endforeach
    </div>
</div>
