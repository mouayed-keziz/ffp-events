{{-- price badge should have light primary background, text primary semibold rounded-sm takes prop text and displays it --}}
@props(['price', 'currency' => 'DZD'])

<span {{ $attributes->merge(['class' => 'bg-primary/10 text-primary font-bold text-sm px-3 py-2 rounded-md inline-flex items-center']) }}>
    {{ number_format($price, 2) }} {{ $currency }}
</span>