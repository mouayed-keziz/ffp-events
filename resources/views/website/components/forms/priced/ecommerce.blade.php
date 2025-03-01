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

    <div class="bg-white border rounded-lg p-4">
        @if(isset($data['products']) && is_array($data['products']) && count($data['products']) > 0)
            <div class="space-y-3">
                @foreach($data['products'] as $index => $product)
                    <div class="flex items-center justify-between border-b pb-3">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                id="product_{{ $index }}_{{ Str::random(5) }}"
                                wire:model.live="{{ $answerPath }}.{{ $product['product_id'] }}.selected"
                                class="checkbox checkbox-sm checkbox-primary mr-3"
                            />
                            <label for="product_{{ $index }}_{{ Str::random(5) }}" class="cursor-pointer">
                                <div class="font-medium">
                                    {{ __('Product ID') }}: {{ $product['product_id'] }}
                                </div>
                            </label>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <div>
                                @include('website.components.forms.priced.price-badge', [
                                    'price' => $product['price'][$this->preferred_currency] ?? 0,
                                    'currency' => $this->preferred_currency
                                ])
                            </div>
                            
                            <div class="flex items-center">
                                <label class="text-xs text-gray-500 me-2">{{ __('Qty') }}:</label>
                                <input 
                                    type="number" 
                                    min="1" 
                                    wire:model.live="{{ $answerPath }}.{{ $product['product_id'] }}.quantity"
                                    class="input input-bordered input-xs w-16 text-center" 
                                    {{ !isset($this->formData[$answerPath][$product['product_id']]['selected']) || 
                                      !$this->formData[$answerPath][$product['product_id']]['selected'] ? 'disabled' : '' }}
                                />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 text-center py-4">
                <x-heroicon-o-shopping-cart class="w-8 h-8 mx-auto mb-2 text-primary/50" />
                <p>{{ __('No products available') }}</p>
            </div>
        @endif
    </div>
</div>