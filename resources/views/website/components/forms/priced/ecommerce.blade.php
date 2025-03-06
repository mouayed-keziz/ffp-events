@props(['data', 'answerPath'])

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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($data['products'] as $product)
                @php
                    $productId = $product['product_id'];
                    $productName = isset($product['product_details'])
                        ? $product['product_details']['name'][app()->getLocale()] ??
                            ($product['product_details']['name']['fr'] ?? "Product {$productId}")
                        : "Product {$productId}";
                    $productCode = isset($product['product_details']) ? $product['product_details']['code'] : null;
                    $productImage = isset($product['product_details']) ? $product['product_details']['image'] : null;

                    // Safely access nested array values using dot notation through wire model path
                    $fullPath = "{$answerPath}.{$productId}";
                    $selectedPath = "formData.{$fullPath}.selected";
                    $quantityPath = "formData.{$fullPath}.quantity";

                    $isSelected = isset($this->{$selectedPath}) ? $this->{$selectedPath} : false;
                    $quantity = isset($this->{$quantityPath}) ? $this->{$quantityPath} : 1;
                @endphp

                <div x-data="{
                    productId: {{ $productId }},
                    selected: {{ $isSelected ? 'true' : 'false' }},
                    quantity: {{ $quantity }},
                    updateProduct() {
                        $wire.set('formData.{{ $answerPath }}.{{ $productId }}.selected', this.selected);
                        $wire.set('formData.{{ $answerPath }}.{{ $productId }}.quantity', this.quantity);
                    }
                }" x-init="$watch('selected', () => updateProduct());
                $watch('quantity', () => updateProduct())"
                    x-bind:class="selected ? 'border-2 border-primary/60 bg-primary/10' : ''"
                    class="rounded-xl overflow-hidden transition-all">

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
                            <input type="checkbox" id="product_{{ $productId }}" x-model="selected"
                                class="checkbox checkbox-sm rounded-md {{ App::getLocale() === 'ar' ? 'ml-2' : '' }}"
                                x-bind:class="selected ? 'checkbox-primary' : ''" />

                            <label for="product_{{ $productId }}"
                                class="font-medium text-base truncate cursor-pointer"
                                title="{{ $productName }}">{{ $productName }}</label>
                        </div>

                        <div class="text-sm text-gray-500 mb-2">{{ __('Code') }}: {{ $productCode ?? '-' }}</div>

                        <div class="grid grid-cols-7 gap-2 items-center">
                            <div class="col-span-7 sm:col-span-4">
                                <input class="input input-sm input-bordered rounded-md w-full" type="number"
                                    x-model="quantity" min="1" step="1" x-bind:disabled="!selected"
                                    x-bind:class="!selected ? 'opacity-60' : ''" placeholder="{{ __('Qty') }}" />
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
