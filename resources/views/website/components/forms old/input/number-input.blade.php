@props(['data', 'answerPath', 'disabled' => false])
<div class="form-control">
    <label class="label">
        <span class="label-text">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>
    <input @if ($disabled) disabled @endif
        placeholder="{{ $data['description'][app()->getLocale()] ?? '' }}"
        class="input input-bordered bg-white mb-2 rounded-md" wire:model.lazy="formData.{{ $answerPath }}"
        @if ($data['required'] ?? false) required @endif>
</div>
