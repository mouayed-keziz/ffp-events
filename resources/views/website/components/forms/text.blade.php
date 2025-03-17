@props(['data', 'answerPath', 'disabled' => false])

<div class="form-control w-full">
    // ...existing code...
    <input type="{{ $type }}"
        class="input input-bordered bg-white rounded-md {{ $disabled ? 'opacity-60 cursor-not-allowed' : '' }}"
        wire:model.live="formData.{{ $answerPath }}" @if ($data['required'] ?? false) required @endif
        {{ $disabled ? 'disabled' : '' }} placeholder="{{ $data['placeholder'][app()->getLocale()] ?? '' }}" />
    // ...existing code...
</div>
