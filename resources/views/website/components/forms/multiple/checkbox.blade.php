@props(['data', 'answerPath', 'disabled' => false])

<div class="form-control my-4">
    <label class="label">
        <span class="label-text font-semibold text-[#546675] text-sm">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>
    @if ($data['description'][app()->getLocale()] ?? false)
        <small
            class="mb-2 font-semibold text-[#83909B] text-sm {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">{{ $data['description'][app()->getLocale()] }}</small>
    @endif
    <div class="flex flex-col gap-2 {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}"
        x-data="{ options: @entangle('formData.' . $answerPath . '.options') }">
        @foreach ($data['options'] as $optionIndex => $option)
            @php
                // Find the corresponding option in our answer structure
                $optionAnswerIndex = -1;

                foreach (data_get($this, 'formData.' . $answerPath . '.options', []) as $idx => $optionData) {
                    $currentValue = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
                    if ($optionData['value'] == $currentValue) {
                        $optionAnswerIndex = $idx;
                        break;
                    }
                }

                $optionLabel = $option['option'][app()->getLocale()] ?? ($option['option']['fr'] ?? '');
            @endphp

            <label class="cursor-pointer flex items-center {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}">
                <input type="checkbox" id="{{ $answerPath }}_{{ $loop->index }}"
                    wire:model.live="formData.{{ $answerPath }}.options.{{ $optionAnswerIndex }}.selected"
                    x-model="options[{{ $optionAnswerIndex }}].selected"
                    :class="{ 'checkbox-primary': options[{{ $optionAnswerIndex }}].selected }"
                    class="checkbox mx-2 rounded-md" @if ($data['required'] ?? false) required @endif
                    {{ $disabled ? 'disabled' : '' }}>
                <span>{{ $optionLabel }}</span>
            </label>
        @endforeach
    </div>
</div>
