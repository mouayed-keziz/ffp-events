<div class="container mx-auto px-4">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">{{ $article->title }}</h1>
        <div class="flex justify-end items-center gap-4 text-xs text-gray-600">
            <div class="flex items-center gap-1">
                @include('website.svg.eye', ['size' => 1])
                <span>{{ $article->views }}</span>
            </div>
            <div class="flex items-center gap-1">
                @include('website.svg.share', ['size' => 1])
                <span>{{ $article->shares_count }}</span>
            </div>
        </div>
    </div>
</div>
