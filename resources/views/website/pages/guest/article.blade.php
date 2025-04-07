{{-- {{dd($viewCount, $article)}} --}}
@extends('website.layouts.app')

@section('content')
    @include('website.components.article.banner', ['article' => $article])
    <main class="w-full max-w-4xl mx-auto px-4 py-8">
        @include('website.components.article.header', ['article' => $article])
    </main>

    <div class="w-full">
        <hr style="height: 1px; background-color: #e2e8f0; border: none;">
    </div>
    @livewire('website.empty')
    <main class="w-full max-w-4xl mx-auto px-4 py-8 flex-grow">
        @include('website.components.article.content', ['article' => $article])
        @if (isset($similarArticles) && count($similarArticles) > 0)
            @include('website.components.article.similar-articles', [
                'similarArticles' => $similarArticles,
            ])
        @endif
    </main>
@endsection
