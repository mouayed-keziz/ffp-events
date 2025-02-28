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

    <div class="space-y-3">
        @foreach ($data['options'] ?? [] as $option)
            <div class="flex items-start">
                <div class="flex items-center flex-wrap gap-2">
                    <input type="checkbox" 
                        id="{{ $answerPath }}_{{ $loop->index }}"
                        class="checkbox checkbox-primary rounded-md mr-2" 
                        wire:model.live="formData.{{ $answerPath }}" 
                        value="{{ $option['option'][app()->getLocale()] ?? '' }}">
                    <label for="{{ $answerPath }}_{{ $loop->index }}" class="cursor-pointer mr-2">
                        {{ $option['option'][app()->getLocale()] ?? $option['option']['fr'] ?? '' }}
                    </label>
                    
                    @if (isset($option['price']))
                        @include('website.components.forms.priced.price-badge', [
                            'price' => $option['price'][$this->preferred_currency] ?? 0,
                            'currency' => $this->preferred_currency
                        ])
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>