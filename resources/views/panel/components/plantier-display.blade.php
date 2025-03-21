@php
    use App\Enums\Currency;

    $locale = app()->getLocale();

    // Get all available currencies
    $currencies = collect(Currency::cases())->pluck('value')->toArray();

    // Get state directly from the component
    $plans = $getState()['plans'] ?? [];
@endphp

<div class="fi-plantier-plans space-y-2">
    @if (!empty($plans))
        <ul class="space-y-3">
            @foreach ($plans as $plan)
                @php
                    $planModel = \App\Models\Plan::find($plan['plan_id']);
                    $planTitle = $planModel
                        ? $planModel->getTranslations('title')[$locale] ?? ($planModel->title ?? '')
                        : '';
                    $planContent = $planModel
                        ? $planModel->getTranslations('content')[$locale] ?? ($planModel->content ?? '')
                        : '';
                    $isSelected = !empty($plan['selected']) && $plan['selected'] === true;
                @endphp
                <li
                    class="fi-resource-item relative flex items-center gap-4 rounded-lg border p-3 transition 
                          {{ $isSelected ? 'border-primary-500 bg-primary-50 dark:border-primary-400 dark:bg-primary-950/50' : 'border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800' }}">
                    <!-- Selection badge - positioned at top of card -->
                    <div class="absolute top-2 right-2 z-10">
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

                    <div class="flex flex-col sm:flex-row gap-3 w-full">
                        <!-- Plan Image -->
                        @if ($planModel && $planModel->hasMedia('image'))
                            <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                                <img src="{{ $planModel->getFirstMediaUrl('image') }}" alt="{{ $planTitle }}"
                                    class="w-full h-full object-cover" />
                            </div>
                        @endif

                        <!-- Plan Info -->
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100 text-base">{{ $planTitle }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1 line-clamp-2">{ $planContent }
                            </p>
                        </div>

                        <!-- Prices -->
                        <div class="flex flex-col gap-2 min-w-[120px] text-right">
                            @foreach ($currencies as $currencyCode)
                                @php
                                    $price = $plan['price'][$currencyCode] ?? 0;
                                    $currencySymbol = match ($currencyCode) {
                                        'EUR' => '€',
                                        'USD' => '$',
                                        'DZD' => 'DA',
                                        default => $currencyCode,
                                    };
                                @endphp
                                <div class="text-xs">
                                    <span
                                        class="font-medium text-gray-500 dark:text-gray-400">{{ $currencyCode }}:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $currencySymbol }}
                                        {{ number_format($price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-gray-500 dark:text-gray-400 italic">{{ __('No plans available') }}</div>
    @endif
</div>
