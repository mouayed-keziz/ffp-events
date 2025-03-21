@php
    use App\Enums\Currency;
    use Filament\Support\Facades\FilamentView;
    use Filament\Support\Colors\Color;

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
                @endphp
                <li
                    class="fi-resource-item relative flex items-center gap-4 rounded-lg border p-3 transition 
                          border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <!-- Selection badge -->
                    <div class="absolute -top-1 -right-1 z-10">
                        @if ($isSelected)
                            <span
                                class="fi-badge rounded-full bg-success-500 text-white text-xs font-medium px-2 py-1 shadow-sm">
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
                        @if ($productModel && $productModel->hasMedia('image'))
                            <img src="{{ $productModel->image }}" alt="{{ $productName }}"
                                class="w-full h-full object-cover" />
                        @else
                            <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('No image') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $productName }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $productCode }}</p>
                    </div>

                    <!-- Prices -->
                    <div class="flex flex-col gap-1 text-right">
                        @foreach ($currencies as $currencyCode)
                            @php
                                $price = $product['price'][$currencyCode] ?? 0;
                                $currencySymbol = match ($currencyCode) {
                                    'EUR' => '€',
                                    'USD' => '$',
                                    'DZD' => 'DA',
                                    default => $currencyCode,
                                };
                            @endphp
                            <div class="text-xs">
                                <span class="font-medium text-gray-500 dark:text-gray-400">{{ $currencyCode }}:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $currencySymbol }}
                                    {{ number_format($price, 2) }}</span>
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
