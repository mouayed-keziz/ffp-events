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
    <input @if ($disabled) disabled @endif type="tel"
        placeholder="{{ $data['description'][app()->getLocale()] ?? '' }}"
        class="input input-bordered bg-white mb-2 rounded-md {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}"
        wire:model.lazy="formData.{{ $answerPath }}" @if ($data['required'] ?? false) required @endif>
</div>
