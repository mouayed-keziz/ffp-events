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
                    $isSelected = !empty($plan['selected']) && $plan['selected'] === true;
                @endphp
                <li
                    class="fi-resource-item relative flex items-center gap-4 rounded-lg border p-3 transition 
                          {{ $isSelected
                              ? 'border-success-600 bg-success-50 dark:border-success-500 dark:bg-success-950/50'
                              : 'border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800' }}">
                    <!-- Selection badge - positioned at top right of card -->
                    <div class="absolute -top-2 -right-2 z-10">
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

                    <div class="flex flex-row gap-3 w-full">
                        <!-- Plan Image -->
                        @if ($planModel && $planModel->hasMedia('image'))
                            <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                                <img src="{{ $planModel->getFirstMediaUrl('image') }}" alt="{{ $planTitle }}"
                                    class="w-full h-full object-cover" />
                            </div>
                        @endif

                        <!-- Plan Info with link to plan tier page -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-lg overflow-hidden">
                            @if ($planModel)
                                <img src="{{ $planModel->image }}" alt="{{ $planTitle }}"
                                    class="h-full aspect-video object-cover " />
                            @else
                                <div
                                    class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-gray-400 dark:text-gray-500 text-xs">{{ __('No image') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            @if ($planModel)
                                <a href="{{ route('filament.admin.resources.plan-tiers.edit', ['record' => $planModel->plan_tier_id ?? '']) }}"
                                    class="hover:underline text-primary-600 dark:text-primary-400">
                                    <h3 class="font-medium text-sm">
                                        {{ $planTitle }}</h3>
                                </a>
                            @else
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 text-base">{{ $planTitle }}
                                </h3>
                            @endif
                        </div>

                        <!-- Simple prices display -->
                        <div class="text-right pt-2">
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
                                    <span class="text-gray-500 dark:text-gray-400">{{ $currencyCode }}: </span>
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
