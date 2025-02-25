@props(['data', 'answerPath'])

<div class="form-control">
    <label class="label">
        <span class="label-text">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>
    <textarea placeholder="{{ $data['description'][app()->getLocale()] ?? '' }}"
        class="textarea textarea-bordered bg-white mb-2 rounded-md"
        wire:model.lazy="formData.{{ $answerPath }}"
        @if ($data['required'] ?? false) required @endif></textarea>
</div>
