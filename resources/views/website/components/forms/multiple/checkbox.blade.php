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
        @foreach ($data['options'] as $option)
            <label class="cursor-pointer flex items-center">
                <input type="checkbox"
                    name="checkboxes[]"
                    wire:model.lazy="formData.{{ $answerPath }}"
                    value="{{ $option['option'][app()->getLocale()] }}"
                    class="checkbox mx-2 rounded-md"
                    @if ($data['required'] ?? false) required @endif>
                <span>{{ $option['option'][app()->getLocale()] }}</span>
            </label>
        @endforeach
    </div>
</div>
