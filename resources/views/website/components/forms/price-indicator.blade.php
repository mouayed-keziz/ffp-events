@props([
    'totalPrice' => 0,
    'currency' => 'DZD',
    'disabled' => false,
])

<div class="fixed {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} bottom-4 z-10 hidden md:block md:w-72">
    <div class="bg-white shadow-lg rounded-btn p-4 border">
        <h3 class="font-semibold mb-4">{{ __('website/exhibit-event.total_order') }}</h3>
        <div class="bg-primary/10 py-2 px-4 rounded-btn">
            <div class="text-primary text-xs font-semibold">{{ __('website/exhibit-event.total') }}</div>
            <div class="font-bold text-md text-primary">
                {{ number_format($totalPrice, 2) }} {{ $currency }}
            </div>
        </div>
        @if (!$disabled)
            <div class="dropdown dropdown-top w-full my-3">
                <div tabindex="0" role="button" class="btn btn-primary btn-sm w-full">
                    {{ $currency }}
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-full">
                    <li><a class="font-bold text-sm px-3 py-2 rounded-md inline-flex items-center whitespace-nowrap m-1 {{ $currency === 'DZD' ? 'bg-primary/10 text-primary' : '' }}"
                            wire:click="changeCurrency('DZD')">DZD</a></li>
                    <li><a class="font-bold text-sm px-3 py-2 rounded-md inline-flex items-center whitespace-nowrap m-1 {{ $currency === 'USD' ? 'bg-primary/10 text-primary' : '' }}"
                            wire:click="changeCurrency('USD')">USD</a></li>
                    <li><a class="font-bold text-sm px-3 py-2 rounded-md inline-flex items-center whitespace-nowrap m-1 {{ $currency === 'EUR' ? 'bg-primary/10 text-primary' : '' }}"
                            wire:click="changeCurrency('EUR')">EUR</a></li>
                </ul>
            </div>
        @endif
    </div>
</div>
