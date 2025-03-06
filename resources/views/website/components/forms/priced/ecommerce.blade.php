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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($data['products'] as $product)
                @php
                    $productId = $product['product_id'];
                    $productName = isset($product['product_details'])
                        ? $product['product_details']['name'][app()->getLocale()] ??
                            ($product['product_details']['name']['fr'] ?? "Product {$productId}")
                        : "Product {$productId}";
                    $productCode = isset($product['product_details']) ? $product['product_details']['code'] : null;
                    $productImage = isset($product['product_details']) ? $product['product_details']['image'] : null;
                    $isSelected =
                        isset($this->formData[$answerPath][$productId]['selected']) &&
                        $this->formData[$answerPath][$productId]['selected'];
                @endphp

                <div x-data="{
                    productId: {{ $productId }},
                    selected: @js($isSelected),
                    quantity: @js($this->formData[$answerPath][$productId]['quantity'] ?? 1),
                    updateProduct() {
                        this.$wire.set('{{ $answerPath }}.{{ $productId }}.selected', this.selected);
                        this.$wire.set('{{ $answerPath }}.{{ $productId }}.quantity', this.quantity);
                    }
                }" x-init="$watch('selected', value => updateProduct())"
                    x-bind:class="selected ? 'border-2 border-primary bg-primary/10' : ''"
                    class="rounded-lg shadow-sm overflow-hidden transition-all">

                    <!-- Product Image -->
                    <div class="aspect-[4/3] bg-gray-100">
                        @if ($productImage)
                            <img src="{{ $productImage }}" alt="{{ $productName }}"
                                class="w-full h-full object-cover object-center rounded-t-lg">
                        @else
                            <div class="h-full flex items-center justify-center">
                                <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-300" />
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <div class="flex items-center space-x-2 mb-1">
                            <input type="checkbox" id="product_{{ $productId }}" x-model="selected"
                                class="checkbox checkbox-primary rounded-md" />

                            <label for="product_{{ $productId }}"
                                class="font-medium text-base truncate cursor-pointer"
                                title="{{ $productName }}">{{ $productName }}</label>
                        </div>

                        <div class="text-sm text-gray-500 mb-2">{{ __('Code') }}: {{ $productCode ?? '-' }}</div>

                        <div class="flex items-center justify-between">
                            <div>
                                @include('website.components.forms.priced.price-badge', [
                                    'price' => $product['price'][$this->preferred_currency] ?? 0,
                                    'currency' => $this->preferred_currency,
                                ])
                            </div>

                            <div x-show="selected" x-transition>
                                <input class="input input-sm input-bordered rounded-md w-24" type="number"
                                    x-model="quantity" min="1" step="1"
                                    placeholder="{{ __('Qty') }}" />
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
