@php
    $size = $size ?? 'md';
    $classes = [
        'sm' => [
            'wrapper' => 'px-4 py-2',
            'numbers' => 'text-2xl',
            'labels' => 'text-xs',
            'gap' => 'gap-4',
        ],
        'md' => [
            'wrapper' => 'px-6 py-3',
            'numbers' => 'text-3xl',
            'labels' => 'text-xs',
            'gap' => 'gap-6',
        ],
        'lg' => [
            'wrapper' => 'px-4 md:px-6 py-3',
            'numbers' => 'text-2xl md:text-4xl',
            'labels' => 'text-xs md:text-sm',
            'gap' => 'gap-4 md:gap-6',
        ],
    ][$size];
@endphp

<div class="inline-block bg-black/10 backdrop-blur-sm rounded-xl {{ $classes['wrapper'] }}">
    <div class="flex {{ $classes['gap'] }} text-white text-center">
        <div>
            <div class="font-bold {{ $classes['numbers'] }}">{{ $countdown['days'] }}</div>
            <div class="{{ $classes['labels'] }}">Jours</div>
        </div>
        <div>
            <div class="font-bold {{ $classes['numbers'] }}">{{ $countdown['hours'] }}</div>
            <div class="{{ $classes['labels'] }}">Heures</div>
        </div>
        <div>
            <div class="font-bold {{ $classes['numbers'] }}">{{ $countdown['minutes'] }}</div>
            <div class="{{ $classes['labels'] }}">Minutes</div>
        </div>
        <div>
            <div class="font-bold {{ $classes['numbers'] }}">{{ $countdown['seconds'] }}</div>
            <div class="{{ $classes['labels'] }}">Secondes</div>
        </div>
    </div>
</div>
