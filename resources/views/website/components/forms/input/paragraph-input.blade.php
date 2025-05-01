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
    <textarea @if ($disabled) disabled @endif
        placeholder="{{ $data['description'][app()->getLocale()] ?? '' }}"
        class="textarea textarea-bordered bg-white mb-2 rounded-md pl-[10px]" wire:model.lazy="formData.{{ $answerPath }}"
        @if ($data['required'] ?? false) required @endif></textarea>
</div>
