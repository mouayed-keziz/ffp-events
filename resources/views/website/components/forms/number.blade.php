@props(['data', 'answerPath', 'disabled' => false])

<div class="form-control w-full">
    <label class="label">
        <span class="label-text font-semibold text-[#546675] text-sm">
            {{ $data['label'][app()->getLocale()] ?? '' }}
            @if ($data['required'] ?? false)
                <span class="text-error">*</span>
            @endif
        </span>
    </label>

    <input type="number"
        class="input input-bordered bg-white rounded-md pl-[10px] {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}"
        wire:model.live="formData.{{ $answerPath }}" @if ($data['required'] ?? false) required @endif
        {{ $disabled ? 'disabled' : '' }} placeholder="{{ $data['placeholder'][app()->getLocale()] ?? '' }}"
        @if (isset($data['min'])) min="{{ $data['min'] }}" @endif
        @if (isset($data['max'])) max="{{ $data['max'] }}" @endif
        @if (isset($data['step'])) step="{{ $data['step'] }}" @endif />

</div>
