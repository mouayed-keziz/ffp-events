@extends('website.layouts.app')

@section('title', $category->name . ' - Categories')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold">{{ $category->name }}</h1>
        <p class="text-gray-600 mt-2">{{ __('website/blog.articles_in_category') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($articles as $article)
            @include('website.components.blog-card', ['article' => $article])
        @endforeach
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
@endsection
