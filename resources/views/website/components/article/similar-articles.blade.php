<div class="container mx-auto px-4 py-8 mb-6">
    <h2 class="text-2xl font-bold mb-6">Articles similaires</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($similarArticles as $article)
            @include('website.components.articles.article-card', [
                'title' => $article->title,
                'date' => $article->published_at ? $article->published_at->format('d F Y') : '',
                'views' => $article->views,
                'image' => $article->getFirstMediaUrl('image') ?: 'https://placehold.co/600x400',
            ])
        @endforeach
    </div>
</div>
