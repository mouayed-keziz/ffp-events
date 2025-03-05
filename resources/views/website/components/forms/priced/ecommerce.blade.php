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
                @foreach($data['products'] as $product)
                    @php
                        $productId = $product['product_id'];
                        $productName = isset($product['product_details']) ? 
                            ($product['product_details']['name'][app()->getLocale()] ?? $product['product_details']['name']['fr'] ?? "Product {$productId}") : 
                            "Product {$productId}";
                        $productImage = isset($product['product_details']) ? 
                            $product['product_details']['image'] : 
                            null;
                    @endphp
                    
                    <div class="border rounded-lg overflow-hidden">
                        <div class="flex flex-wrap md:flex-nowrap">
                            <!-- Product Image -->
                            @if($productImage)
                                <div class="w-full md:w-40 h-40 bg-gray-100">
                                    <img src="{{ $productImage }}" alt="{{ $productName }}" 
                                        class="w-full h-full object-cover object-center">
                                </div>
                            @else
                                <div class="w-full md:w-40 h-40 bg-gray-100 flex items-center justify-center">
                                    <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-300" />
                                </div>
                            @endif
                            
                            <!-- Product Info -->
                            <div class="flex-grow p-4">
                                <div class="flex flex-wrap justify-between items-start">
                                    <div class="mb-2">
                                        <div class="font-medium text-lg">{{ $productName }}</div>
                                        <div class="text-sm text-gray-500">{{ __('ID') }}: {{ $productId }}</div>
                                    </div>
                                    
                                    <div class="text-right">
                                        @include('website.components.forms.priced.price-badge', [
                                            'price' => $product['price'][$this->preferred_currency] ?? 0,
                                            'currency' => $this->preferred_currency
                                        ])
                                    </div>
                                </div>
                                
                                <!-- Selection & Quantity -->
                                <div class="flex flex-wrap mt-4 md:mt-8 items-center justify-between">
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            id="product_{{ $productId }}"
                                            wire:model.live="{{ $answerPath }}.{{ $productId }}.selected"
                                            class="checkbox checkbox-sm checkbox-primary mr-3" 
                                        />
                                        <label for="product_{{ $productId }}" class="cursor-pointer select-none">
                                            {{ __('Select Product') }}
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center mt-2 md:mt-0">
                                        <label class="text-xs text-gray-500 me-2">{{ __('Quantity') }}:</label>
                                        <div class="join">
                                            <button type="button"
                                                class="join-item btn btn-xs btn-outline"
                                                wire:click="$set('{{ $answerPath }}.{{ $productId }}.quantity', 
                                                    Math.max(1, parseInt({{ $answerPath }}.{{ $productId }}.quantity || 1) - 1))"
                                                {{ !isset($this->formData[$answerPath][$productId]['selected']) || 
                                                    !$this->formData[$answerPath][$productId]['selected'] ? 'disabled' : '' }}
                                            >-</button>
                                            
                                            <input 
                                                type="number" 
                                                min="1" 
                                                wire:model.live="{{ $answerPath }}.{{ $productId }}.quantity"
                                                class="join-item input input-xs input-bordered w-16 text-center" 
                                                {{ !isset($this->formData[$answerPath][$productId]['selected']) || 
                                                    !$this->formData[$answerPath][$productId]['selected'] ? 'disabled' : '' }}
                                            />
                                            
                                            <button type="button"
                                                class="join-item btn btn-xs btn-outline"
                                                wire:click="$set('{{ $answerPath }}.{{ $productId }}.quantity', 
                                                    parseInt({{ $answerPath }}.{{ $productId }}.quantity || 1) + 1)"
                                                {{ !isset($this->formData[$answerPath][$productId]['selected']) || 
                                                    !$this->formData[$answerPath][$productId]['selected'] ? 'disabled' : '' }}
                                            >+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-gray-500 text-center py-8">
                <x-heroicon-o-shopping-cart class="w-12 h-12 mx-auto mb-2 text-primary/50" />
                <p>{{ __('No products available') }}</p>
            </div>
        @endif
    </div>
</div>