@props(['title', 'date', 'views', 'image'])

<div class="card bg-base-100 shadow-xl rounded-btn overflow-hidden">
    <!-- Image container -->
    <div class="relative">
        <img src="{{ $image }}" class="w-full h-[250px] object-cover" alt="{{ $title }}" />
    </div>

    <div class="card-body p-6">
        <!-- Date -->
        <p class="text-xs text-gray-500">{{ $date }}</p>

        <!-- Title -->
        <h2 class="font-bold text-md">{{ $title }}</h2>

        <!-- Interactions -->
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
