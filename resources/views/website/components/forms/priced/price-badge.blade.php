@props(['price', 'currency' => 'DZD'])

<span
    {{ $attributes->merge(['class' => 'bg-primary/10 text-primary font-bold text-sm px-3 py-2 rounded-md inline-flex items-center whitespace-nowrap']) }}>
    {{ number_format($price, 2) }} {{ $currency }}
</span>
