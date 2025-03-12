<div class="container mx-auto px-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">{{ $article->title }}</h1>
        <div class="flex justify-end items-center gap-4 text-xs text-gray-600 mt-4">
            <div class="flex items-center gap-1">
                <x-heroicon-o-eye class="h-4 w-4" />
                <span>{{ $article->views }}</span>
            </div>
            <div class="flex items-center gap-1">
                <x-heroicon-o-share class="h-4 w-4" />
                <span>{{ $article->shares_count }}</span>
            </div>
        </div>
    </div>
</div>
