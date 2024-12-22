@php
$hasImage = $getRecord()->hasMedia('image');
$imageUrl = $hasImage ? $getRecord()->getFirstMediaUrl('image') : null;
@endphp

<x-filament::section>
    <x-slot name="heading">
        {{ $getRecord()->title }}
    </x-slot>

    <div style="position: relative; width: 100%; aspect-ratio: 21/9; border-radius: 0.5rem; overflow: hidden;">
        @if ($hasImage)
        <img src="{{ $imageUrl }}" alt="{{ $getRecord()->title }}" style="width: 100%; height: 100%; object-fit: cover;">
        @else
        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
            <h1 style="font-size: 1.5rem; font-weight: bold;">{{ $getRecord()->title }}</h1>
        </div>
        @endif
    </div>
</x-filament::section>