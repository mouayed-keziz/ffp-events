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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($data['products'] ?? [] as $product)
            <div class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow">
                <div class="card-body">
                    <h3 class="card-title text-base">{{ $product['product'][app()->getLocale()] ?? '' }}</h3>
                    <p class="text-sm">{{ $product['description'][app()->getLocale()] ?? '' }}</p>
                    <p class="font-bold text-primary">{{ number_format($product['price']['DZD'] ?? 0, 2) }} DZD</p>
                    
                    <div class="card-actions justify-end mt-2">
                        <label class="cursor-pointer flex items-center">
                            <input type="radio" 
                                class="radio radio-primary mr-2" 
                                wire:model.defer="formData.{{ $answerPath }}"
                                value="{{ $product['product'][app()->getLocale()] ?? '' }}" />
                            {{ __('Select') }}
                        </label>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        <label class="label">
            <span class="label-text text-sm">{{ __('Quantity') }}</span>
        </label>
        <input type="number" min="1" class="input input-bordered w-full md:w-1/3" 
            wire:model.defer="formData.{{ str_replace('answer', 'quantity', $answerPath) }}" />
    </div>
</div>

{{-- TODO: Implement full ecommerce functionality with:
    1. Product images
    2. Product variants (size, color, etc.)
    3. Add to cart functionality
--}}