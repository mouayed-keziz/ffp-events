@props(['data', 'answerPath', 'disabled' => false])

<div class="mb-4">
    <label class="label">
        <span
            class="label-text font-semibold text-[#546675] text-sm {{ isset($data['required']) && $data['required'] ? 'required' : '' }}">
            {{ $data['label'][app()->getLocale()] ?? ($data['label']['fr'] ?? '') }}
        </span>
        @if (isset($data['description']) && !empty($data['description'][app()->getLocale()]))
            <span
                class="label-text-alt font-semibold text-[#83909B] text-sm {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">
                {{ $data['description'][app()->getLocale()] }}
            </span>
        @endif
    </label>

    @if (isset($data['products']) && is_array($data['products']) && count($data['products']) > 0)
        @php
            // Initialize products array in answer structure if it doesn't exist
$productsData = data_get($this, 'formData.' . $answerPath . '.products', []);
if (empty($productsData)) {
    // Initialize the answer with all available products
    $productsData = collect($data['products'])
        ->map(function ($product) use ($data) {
            $productModel = isset($product['product_details']) ? $product['product_details'] : [];
            return [
                'product_id' => $product['product_id'] ?? null,
                'name' => $productModel['name'] ?? null,
                'code' => $productModel['code'] ?? null,
                'selected' => false,
                'number1' => 1,
                'number2' => 1,
                'number1_label' => $data['number1_label'] ?? null,
                'number2_label' => $data['number2_label'] ?? null,
                'price' => $product['price'] ?? [],
            ];
        })
        ->toArray();

    // Set the initial products data in the model
    data_set($this, 'formData.' . $answerPath . '.products', $productsData);
            }
        @endphp

        <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }}">
            @foreach ($data['products'] as $productIndex => $product)
                @php
                    // Find the corresponding product in our answer structure
                    $productAnswerIndex = -1;
                    $isSelected = false;
                    $number1 = 1;
                    $number2 = 1;

                    foreach (data_get($this, 'formData.' . $answerPath . '.products', []) as $idx => $productData) {
                        if ($productData['product_id'] == $product['product_id']) {
                            $productAnswerIndex = $idx;
                            $isSelected = !empty($productData['selected']) && $productData['selected'] === true;
                            $number1 = intval($productData['number1'] ?? 1);
                            $number2 = intval($productData['number2'] ?? 1);
                            break;
                        }
                    }

                    $productId = $product['product_id'];
                    $productName = isset($product['product_details']) ? $product['product_details']['name'] : null;
                    $productCode = isset($product['product_details']) ? $product['product_details']['code'] : null;
                    $productImage = isset($product['product_details']) ? $product['product_details']['image'] : null;

                    $number1Label = $data['number1_label'][app()->getLocale()] ?? __('Width');
                    $number2Label = $data['number2_label'][app()->getLocale()] ?? __('Height');
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
                                class="checkbox checkbox-sm rounded-md {{ App::getLocale() === 'ar' ? 'ml-2' : '' }} {{ $disabled ? 'cursor-not-allowed' : '' }} {{ $isSelected ? 'checkbox-primary' : 'checkbox-primary' }}"
                                {{ $disabled ? 'disabled' : '' }} />

                            <label for="product_{{ $productId }}"
                                class="font-semibold text-[#546675] text-sm truncate {{ $disabled ? 'cursor-not-allowed' : 'cursor-pointer' }}"
                                title="{{ $productName }}">{{ $productName }}</label>
                        </div>

                        <div class="mt-4 mb-1 font-semibold text-[#83909B] text-sm">
                            <span class="label-text">
                                {{ $number1Label }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <input
                                class="input input-sm input-bordered rounded-md w-full {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }} {{ $disabled ? 'cursor-not-allowed' : '' }}"
                                type="number"
                                wire:model.live="formData.{{ $answerPath }}.products.{{ $productAnswerIndex }}.number1"
                                wire:change="$refresh" min="1" step="1"
                                {{ !$isSelected || $disabled ? 'disabled' : '' }}
                                class="{{ !$isSelected || $disabled ? 'opacity-60' : '' }}"
                                placeholder="{{ $number1Label }}" />
                        </div>

                        <div class="mt-3 mb-1 font-semibold text-[#83909B] text-sm">
                            <span class="label-text">
                                {{ $number2Label }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <input
                                class="input input-sm input-bordered rounded-md w-full {{ app()->getLocale() === 'ar' ? 'pr-[10px]' : 'pl-[10px]' }} {{ $disabled ? 'cursor-not-allowed' : '' }}"
                                type="number"
                                wire:model.live="formData.{{ $answerPath }}.products.{{ $productAnswerIndex }}.number2"
                                wire:change="$refresh" min="1" step="1"
                                {{ !$isSelected || $disabled ? 'disabled' : '' }}
                                class="{{ !$isSelected || $disabled ? 'opacity-60' : '' }}"
                                placeholder="{{ $number2Label }}" />
                        </div>

                        <div class="mt-4 flex justify-end">
                            <div class="flex-shrink-0 text-right whitespace-nowrap">
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
