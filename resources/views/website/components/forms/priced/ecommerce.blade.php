@props(['data', 'answerPath', 'disabled' => false])

<div class="mb-4">
    <label class="label">
        <span class="label-text font-medium {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? ($data['label']['fr'] ?? '') }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span class="label-text-alt text-xs text-gray-500">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    @if (isset($data['products']) && is_array($data['products']) && count($data['products']) > 0)
        @php
            // Initialize products array in answer structure if it doesn't exist
// Prepend formData. to the answerPath when accessing data
$productsData = data_get($this, 'formData.' . $answerPath . '.products', []);
if (empty($productsData)) {
    // Initialize the answer with all available products
    $productsData = collect($data['products'])
        ->map(function ($product) {
            $productModel = isset($product['product_details']) ? $product['product_details'] : [];
            return [
                'product_id' => $product['product_id'] ?? null,
                'name' => $productModel['name'] ?? null,
                'code' => $productModel['code'] ?? null,
                'selected' => false,
                'quantity' => 1,
                'price' => $product['price'] ?? [],
            ];
        })
        ->toArray();

    // Set the initial products data in the model - prepend formData. to the path
    data_set($this, 'formData.' . $answerPath . '.products', $productsData);
            }
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($data['products'] as $productIndex => $product)
                @php
                    // Find the corresponding product in our answer structure
                    $productAnswerIndex = -1;
                    $isSelected = false;
                    $quantity = 1;

                    foreach (data_get($this, 'formData.' . $answerPath . '.products', []) as $idx => $productData) {
                        if ($productData['product_id'] == $product['product_id']) {
                            $productAnswerIndex = $idx;
                            $isSelected = !empty($productData['selected']) && $productData['selected'] === true;
                            $quantity = intval($productData['quantity'] ?? 1);
                            break;
                        }
                    }

                    $productId = $product['product_id'];
                    $productName = isset($product['product_details']) ? $product['product_details']['name'] : null;
                    $productCode = isset($product['product_details']) ? $product['product_details']['code'] : null;
                    $productImage = isset($product['product_details']) ? $product['product_details']['image'] : null;
                @endphp

                <div wire:key="product-{{ $productId }}"
                    class="rounded-xl overflow-hidden transition-all {{ $isSelected ? 'border-2 border-primary/60 bg-primary/10' : '' }} {{ $disabled ? 'opacity-60' : '' }}">

                    <!-- Product Image -->
                    <div class="aspect-[56/43] px-1.5 pt-1.5">
                        @if ($productImage)
                            <img src="{{ $productImage }}" alt="{{ $productName }}"
                                class="w-full h-full object-cover object-center rounded-xl">
                        @else
                            <div class="h-full flex items-center justify-center">
                                <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-300" />
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="px-4 py-2">
                        <div class="flex items-center space-x-2 mb-1">
                            <input type="checkbox" id="product_{{ $productId }}"
                                wire:model.live="formData.{{ $answerPath }}.products.{{ $productAnswerIndex }}.selected"
                                wire:change="updateProductQuantity('{{ $answerPath }}', {{ $productId }}, $event.target.checked ? {{ $quantity }} : 0)"
                                class="checkbox checkbox-sm rounded-md {{ App::getLocale() === 'ar' ? 'ml-2' : '' }} {{ $disabled ? 'cursor-not-allowed' : '' }}"
                                {{ $disabled ? 'disabled' : '' }}
                                class="checkbox checkbox-sm {{ $isSelected ? 'checkbox-primary' : '' }}" />

                            <label for="product_{{ $productId }}"
                                class="font-medium text-sm text-base truncate {{ $disabled ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                title="{{ $productName }}">{{ $productName }}</label>
                        </div>

                        {{-- <div class="text-sm text-gray-500 mb-2">{{ __('Code') }}: {{ $productCode ?? '-' }}</div> --}}

                        <div class="mt-4 mb-1 text-red-300 text-sm font-semibold">
                            <span
                                class="label-text 
                                {{ isset($data['quantity_label']) && $data['quantity_label'] ? 'required' : '' }}">
                                {{ $data['quantity_label'][app()->getLocale()] ?? __('Qty') }}
                            </span>
                        </div>
                        <div class="grid grid-cols-7 gap-2 items-center">
                            <div class="col-span-7 sm:col-span-4">
                                <input
                                    class="input input-sm input-bordered rounded-md w-full {{ $disabled ? 'cursor-not-allowed' : '' }}"
                                    type="number"
                                    wire:model.live="formData.{{ $answerPath }}.products.{{ $productAnswerIndex }}.quantity"
                                    wire:change="$refresh" min="1" step="1"
                                    {{ !$isSelected || $disabled ? 'disabled' : '' }}
                                    class="{{ !$isSelected || $disabled ? 'opacity-60' : '' }}"
                                    placeholder="{{ $data['quantity_label'][app()->getLocale()] ?? __('Qty') }}" />
                            </div>

                            <div class="col-span-7 sm:col-span-3 text-right">
                                @include('website.components.forms.priced.price-badge', [
                                    'price' => $product['price'][$this->preferred_currency ?? 'DZD'] ?? 0,
                                    'currency' => $this->preferred_currency ?? 'DZD',
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white border rounded-lg p-8 text-center">
            <x-heroicon-o-shopping-cart class="w-12 h-12 mx-auto mb-2 text-primary/50" />
            <p>{{ __('No products available') }}</p>
        </div>
    @endif
</div>
