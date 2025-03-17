@props(['data', 'answerPath', 'disabled' => false])

<div class="form-control w-full">
    <label class="label">
        <span class="label-text">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>

    <textarea
        class="textarea textarea-bordered bg-white rounded-md h-32 {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}"
        wire:model.live="formData.{{ $answerPath }}" @if ($data['required'] ?? false) required @endif
        {{ $disabled ? 'disabled' : '' }} placeholder="{{ $data['placeholder'][app()->getLocale()] ?? '' }}"></textarea>

</div>
