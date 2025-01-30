@php
    $placeholderImages = [
        'https://placehold.co/600x400',
        'https://placehold.co/600x400',
        'https://placehold.co/600x400',
    ];
@endphp

<div class="relative overflow-hidden rounded-btn mb-12 aspect-[7/4] md:aspect-video lg:aspect-[3/1]">
    <!-- Slides -->
    <div class="w-full h-full flex transition-all duration-300">
        @foreach ($placeholderImages as $index => $image)
            <div class="flex-shrink-0 w-full h-full">
                <img src="{{ $image }}" class="w-full h-full object-cover" alt="Slide {{ $index + 1 }}">
            </div>
        @endforeach
    </div>
    <!-- Pagination -->
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex space-x-2">
        @foreach ($placeholderImages as $index => $image)
            <button
                class="h-3 {{ $index === 0 ? 'w-6 bg-primary' : 'w-3 bg-primary/20' }} rounded-full border-0"></button>
        @endforeach
    </div>
</div>
