@extends('website.layouts.app')

@section('title', $article->title)

@section('content')
    <article class="prose prose-lg max-w-4xl mx-auto">
        @if ($article->hasMedia('image'))
            <img src="{{ $article->getFirstMediaUrl('image') }}" alt="{{ $article->title }}"
                class="w-full h-[400px] object-cover rounded-lg shadow-lg" />
        @endif

        <div class="mt-8">
            <h1>{{ $article->title }}</h1>

            <div class="flex gap-2 my-4">
                @foreach ($article->categories as $category)
                    <a href="{{ route('blog.category', $category) }}" class="badge badge-outline">{{ $category->name }}</a>
                @endforeach
            </div>

            <div class="text-gray-600 mb-8">
                Published {{ $article->published_at->diffForHumans() }}
            </div>

            <div class="mb-6">
                {{ $article->description }}
            </div>

            <div class="prose max-w-none">
                {!! $article->content !!}
            </div>
        </div>

        <div class="mt-8 pt-8 border-t">
            <a href="{{ route('blog.index') }}" class="btn btn-outline">
                ← Back to Blog
            </a>
        </div>
    </article>
@endsection
