@props(['data', 'answerPath'])
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
}

// Find the selected value if any
$selectedValue = data_get($this, 'formData.' . $answerPath . '.selectedValue');
    @endphp

    <div class="flex flex-col gap-2">
        @php
            $radioName = 'radio_' . str_replace('.', '_', $answerPath);
        @endphp

        @foreach ($data['options'] as $option)
            @php
                // Get the current option value
                $optionLabel = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
                $isSelected = $selectedValue === $optionLabel;
            @endphp

            <label class="cursor-pointer flex items-center">
                <input type="radio" name="{{ $radioName }}" value="{{ $optionLabel }}"
                    wire:model.live="formData.{{ $answerPath }}.selectedValue"
                    wire:change="updateRadioSelection('{{ $answerPath }}', $event.target.value)"
                    {{ $isSelected ? 'checked' : '' }} class="radio mx-2"
                    @if ($data['required'] ?? false) required @endif>
                <span>{{ $optionLabel }}</span>
            </label>
        @endforeach
    </div>

    {{-- Debug information to help troubleshoot --}}
    @include('website.components.forms.debug-path', [
        'answerPath' => $answerPath,
    ])
</div>
