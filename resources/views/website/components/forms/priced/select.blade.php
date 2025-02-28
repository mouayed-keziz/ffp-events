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

    <div class="flex flex-col space-y-2 md:flex-row md:space-y-0 md:space-x-2 items-end">
        <div class="w-full md:w-2/3">
            <select wire:model.defer="formData.{{ $answerPath }}" class="select select-bordered w-full" {{ isset($data['required']) && $data['required'] ? 'required' : '' }}>
                <option value="">{{ __('Select an option') }}</option>
                @foreach ($data['options'] ?? [] as $option)
                    <option value="{{ $option['option'][app()->getLocale()] ?? '' }}">
                        {{ $option['option'][app()->getLocale()] ?? $option['option']['fr'] ?? '' }}
                        @if (isset($option['price']))
                            - {{ number_format($option['price']['DZD'] ?? 0, 2) }} DZD
                        @endif
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-1/3">
            <label class="label">
                <span class="label-text text-sm">{{ __('Quantity') }}</span>
            </label>
            <input type="number" min="1" class="input input-bordered w-full" 
                wire:model.defer="formData.{{ str_replace('answer', 'quantity', $answerPath) }}" />
        </div>
    </div>
</div>