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
    <div class="flex flex-col gap-2">
        @php
            $radioName = 'radio_' . str_replace('.', '_', $answerPath);
        @endphp
        @foreach ($data['options'] as $option)
            <label class="cursor-pointer flex items-center">
                <input type="radio"
                    wire:model.lazy="formData.{{ $answerPath }}"
                    name="{{ $radioName }}"
                    value="{{ $option['option'][app()->getLocale()] }}"
                    class="radio mx-2"
                    @if ($data['required'] ?? false) required @endif>
                <span>{{ $option['option'][app()->getLocale()] }}</span>
            </label>
        @endforeach
    </div>
</div>
