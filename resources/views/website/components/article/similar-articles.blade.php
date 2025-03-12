<div class="container mx-auto px-4 py-8 mb-6">
    <h2 class="text-2xl font-bold mb-6">{{ __('website/articles.similar_articles') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($similarArticles as $article)
            @include('website.components.articles.article-card', [
                'title' => $article->title,
                'slug' => $article->slug,
                'date' => $article->published_at ? $article->published_at->format('d F Y') : '',
                'views' => $article->views,
                'shares' => $article->shares_count,
                'image' => $article->getFirstMediaUrl('image') ?: asset('placeholder.png'),
            ])
        @endforeach
    </div>
</div>
