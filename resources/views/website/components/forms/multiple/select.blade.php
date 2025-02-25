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
    <select class="select select-bordered bg-white mb-2 rounded-md"
        wire:model.lazy="formData.{{ $answerPath }}"
        @if ($data['required'] ?? false) required @endif>
        {{-- If description exists, display a default disabled option --}}
        @if ($data['description'][app()->getLocale()] ?? false)
            <option value="" disabled selected>{{ $data['description'][app()->getLocale()] }}</option>
        @endif
        {{-- Loop through options --}}
        @foreach ($data['options'] as $option)
            <option value="{{ $option['option'][app()->getLocale()] }}">{{ $option['option'][app()->getLocale()] }}</option>
        @endforeach
    </select>
</div>
