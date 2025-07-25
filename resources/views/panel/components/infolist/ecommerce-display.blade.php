@php
    use App\Enums\Currency;
    use Filament\Support\Facades\FilamentView;
    use Filament\Support\Colors\Color;
    use Filament\Pages\Actions\Action;

    $locale = app()->getLocale();

    // Get all available currencies
    $currencies = collect(Currency::cases())->pluck('value')->toArray();

    // Fix for $getState() call - use Laravel's view data or default to empty array
$state = $getState() ?? [];
$products = $state['products'] ?? [];
@endphp

<div class="fi-ecommerce-products space-y-2">
    @if (!empty($products))
        <ul class="space-y-2">
            @foreach ($products as $product)
                @php
                    $productModel = \App\Models\Product::find($product['product_id']);
                    $productName = $productModel
                        ? $productModel->getTranslations('name')[$locale] ?? $product['name']
                        : $product['name'];
                    $productCode = $product['code'] ?? ($productModel ? $productModel->code : '');
                    $isSelected = !empty($product['selected']) && $product['selected'] === true;
                    $quantity = $product['quantity'] ?? 1; // Get quantity with default of 1
                @endphp
                <li
                    class="fi-resource-item relative flex items-center gap-4 rounded-lg border p-3 transition
                        {{ $isSelected
                            ? 'border-primary-600 bg-primary-600/10 dark:border-primary-500 dark:bg-primary-600/10'
                            : 'border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800' }}">
                    <!-- Selection badge - positioned at top right of card -->
                    <div class="absolute -top-2 -right-2 z-10">
                        @if ($isSelected)
                            <span
                                class="fi-badge rounded-full bg-primary-500 text-white text-xs font-medium px-2 py-1 shadow-sm">
                                {{ __('Selected') }}
                            </span>
                        @else
                            <span
                                class="fi-badge rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 text-xs font-medium px-2 py-1 shadow-sm">
                                {{ __('Not Selected') }}
                            </span>
                        @endif
                    </div>

                    <!-- Product Image -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden">
                        @if ($productModel)
                            <img src="{{ $productModel->image }}" alt="{{ $productName }}"
                                class="h-full aspect-video object-cover " />
                        @else
                            <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('No image') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info with link to product page -->
                    <div class="flex-1">
                        @if ($productModel)
                            <a href="{{ route('filament.admin.resources.products.edit', ['record' => $productModel->id]) }}"
                                class="hover:underline text-primary-600 dark:text-primary-400">
                                <h3 class="font-medium text-sm">{{ $productName }} x ({{ $quantity }})</h3>
                            </a>
                        @else
                            <h3 class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $productName }} x
                                ({{ $quantity }})
                            </h3>
                        @endif
                        <p class="text-gray-500 dark:text-gray-400 text-xs">code : <span
                                class="font-bold">{{ $productCode }}</span></p>
                        @if ($isSelected)
                            <p class="text-gray-500 dark:text-gray-400 text-xs">quantity : <span
                                    class="font-bold">{{ $quantity }}</span></p>
                        @endif
                    </div>

                    <!-- Prices display in compact format -->
                    <div class="text-right pt-2">
                        @foreach ($currencies as $currencyCode)
                            @php
                                // Ensure price is numeric, convert string to float/int, fallback to 0
                                $price = is_numeric($product['price'][$currencyCode] ?? 0)
                                    ? (float) ($product['price'][$currencyCode] ?? 0)
                                    : 0;

                                // Ensure quantity is numeric, convert string to int, fallback to 1
                                $numericQuantity = is_numeric($quantity) ? (int) $quantity : 1;

                                // Safe multiplication with guaranteed numeric values
                                $totalPrice = $price * $numericQuantity;
                            @endphp
                            <div class="text-xs mb-1">
                                <span class="text-gray-500 dark:text-gray-400">
                                    {{ $currencyCode }}:
                                </span>
                                @if ($isSelected)
                                    <span class="text-gray-900 dark:text-gray-100">
                                        <span class="font-bold text-primary-600 dark:text-primary-400">
                                            {{ number_format($price, 2) }}
                                        </span> x
                                        {{ $numericQuantity }} = <span
                                            class="font-bold text-primary-600 dark:text-primary-400">
                                            {{ number_format($totalPrice, 2) }}
                                        </span>
                                    </span>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">
                                        {{ number_format($price, 2) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-gray-500 dark:text-gray-400 italic">{{ __('No products available') }}</div>
    @endif
</div>
