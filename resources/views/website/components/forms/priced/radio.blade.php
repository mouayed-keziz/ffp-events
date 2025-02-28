@props(['data', 'answerPath'])

<div class="mb-4">
    <label class="label">
        <span class="label-text font-medium {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? $data['label']['fr'] ?? '' }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span class="label-text-alt text-xs text-gray-500">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    <div class="space-y-2">
        @foreach ($data['options'] ?? [] as $option)
            <div class="flex items-center space-x-2">
                <input type="radio" 
                    id="{{ $answerPath }}_{{ $loop->index }}"
                    class="radio radio-primary" 
                    wire:model.defer="formData.{{ $answerPath }}" 
                    value="{{ $option['option'][app()->getLocale()] ?? '' }}">
                <label for="{{ $answerPath }}_{{ $loop->index }}" class="cursor-pointer text-sm">
                    {{ $option['option'][app()->getLocale()] ?? $option['option']['fr'] ?? '' }}
                    @if (isset($option['price']))
                        - {{ number_format($option['price']['DZD'] ?? 0, 2) }} DZD
                    @endif
                </label>
            </div>
        @endforeach
    </div>
    
    <div class="mt-2">
        <label class="label">
            <span class="label-text text-sm">{{ __('Quantity') }}</span>
        </label>
        <input type="number" min="1" class="input input-bordered w-full md:w-1/3" 
            wire:model.defer="formData.{{ str_replace('answer', 'quantity', $answerPath) }}" />
    </div>
</div>