@props(['title', 'date', 'views', 'image'])

<div class="card bg-white shadow-md rounded-btn overflow-hidden">
    <div class="relative">
        <img src="{{ $image }}" class="w-full h-[200px] object-cover" alt="{{ $title }}" />
        <!-- Circle overlay with logo positioned at 25% from left -->
        <div class="absolute bottom-0 left-12 transform -translate-x-1/2 -mb-6 bg-white rounded-full">
            @include('website.components.brand.logo-no-text')
        </div>
    </div>

    <div class="card-body p-6 mt-6">
        <p class="text-xs text-gray-500">{{ $date }}</p>
        <h2 class="font-bold text-sm">{{ $title }}</h2>
        <div class="flex justify-end items-center gap-4 text-xs text-gray-600 mt-4">
            <div class="flex items-center gap-1">
                <x-heroicon-o-eye class="h-4 w-4" />
                <span>{{ $views }}</span>
            </div>
            <div class="flex items-center gap-1">
                <x-heroicon-o-share class="h-4 w-4" />
                <span>4</span>
            </div>
        </div>
    </div>
</div>
