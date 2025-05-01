@props(['data', 'answerPath', 'disabled' => false])

<div class="form-control">
    <label class="label">
        <span class="label-text font-semibold text-[#546675] text-sm">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>

    @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
        <small
            class="mb-2 label-text-alt font-semibold text-[#83909B] text-sm {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">{{ $data['description'][app()->getLocale()] }}</small>
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
}

// Find the currently selected option
$selectedOptionIndex = -1;
$selectedOptionLabel = '';

foreach ($optionsData as $idx => $optionData) {
    if (!empty($optionData['selected']) && $optionData['selected'] === true) {
        $selectedOptionIndex = $idx;
        $selectedOptionLabel = $optionData['value'] ?? '';
                break;
            }
        }
    @endphp

    <select
        class="select select-bordered bg-white mb-2 rounded-md {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }} {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}"
        wire:change="updateSelectOption('{{ $answerPath }}', $event.target.value)"
        @if ($data['required'] ?? false) required @endif {{ $disabled ? 'disabled' : '' }}>

        {{-- If description exists, display a default disabled option --}}
        <option value="" {{ $selectedOptionLabel ? '' : 'selected' }} disabled>
            {{ $data['description'][app()->getLocale()] ?? __('Select an option') }}
        </option>

        {{-- Loop through options --}}
        @foreach ($data['options'] as $optionIndex => $option)
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

                $optionLabel = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
            @endphp

            <option value="{{ $optionLabel }}" {{ $isSelected ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
