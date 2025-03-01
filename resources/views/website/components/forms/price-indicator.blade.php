@props([
    'totalPrice' => 0,
    'currency' => 'DZD'
])

<div class="fixed {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} bottom-4 z-10 hidden md:block md:w-72">
    <div class="bg-white shadow-lg rounded-btn p-4 border">
        <h3 class="font-semibold mb-4">{{ __('forms.Total Order') }}</h3>
        <div class="bg-primary/10 py-2 px-4 rounded-btn">
            <div class="text-primary text-xs font-semibold">{{ __('forms.Total Price') }}</div>
            <div class="font-bold text-md text-primary">
                {{ number_format($totalPrice, 2) }} {{ $currency }}
            </div>
        </div>
    </div>
</div>