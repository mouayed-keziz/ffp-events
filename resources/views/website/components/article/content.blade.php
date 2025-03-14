<div class="container mx-auto px-4 py-8">
    <div class="prose max-w-none">
        {!! $article->content !!}
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    <hr class="border-y-1 border-dashed border-gray-300">
    <div class="flex justify-between items-center my-6">
        @include('website.components.article.tags', ['tags' => $article->categories])
        @livewire('website.share', [
            'title' => $article->title,
            'description' => $article->description,
            'url' => route('article', $article),
            'model' => $article,
        ])
    </div>
    <hr class="border-t-1 border-dashed border-gray-300">
</div>
