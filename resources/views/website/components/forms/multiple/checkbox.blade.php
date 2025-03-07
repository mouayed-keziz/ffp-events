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
        }
    @endphp

    <div class="flex flex-col gap-2">
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

            <label class="cursor-pointer flex items-center">
                <input type="checkbox" id="{{ $answerPath }}_{{ $loop->index }}"
                    wire:model.live="formData.{{ $answerPath }}.options.{{ $optionAnswerIndex }}.selected"
                    class="checkbox mx-2 rounded-md" {{ $isSelected ? 'checked' : '' }}
                    @if ($data['required'] ?? false) required @endif>
                <span>{{ $optionLabel }}</span>
            </label>
        @endforeach
    </div>

    {{-- Debug information to help troubleshoot --}}
    @if (config('app.debug'))
        <div class="mt-2 p-2 bg-gray-100 text-xs rounded">
            <p>Debug - Answer Path: {{ $answerPath }}</p>
            <pre>@json(data_get($this, 'formData.' . $answerPath), JSON_PRETTY_PRINT)</pre>
        </div>
    @endif
</div>
