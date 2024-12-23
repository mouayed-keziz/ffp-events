@extends('website.layouts.app')

@section('title', 'Blog')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold">Our Blog</h1>
        <p class="text-gray-600 mt-2">Discover our latest articles and insights</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($articles as $article)
            <div class="card bg-base-100 shadow-xl">
                @if($article->hasMedia('image'))
                    <figure>
                        <img src="{{ $article->getFirstMediaUrl('image') }}" alt="{{ $article->title }}" class="h-48 w-full object-cover" />
                    </figure>
                @endif
                <div class="card-body">
                    <h2 class="card-title">{{ $article->title }}</h2>
                    <p class="text-gray-600 line-clamp-2">{{ $article->description }}</p>
                    <div class="card-actions justify-between items-center mt-4">
                        <div class="flex gap-2">
                            @foreach($article->categories as $category)
                                <a href="{{ route('blog.category', $category) }}" class="badge badge-outline">{{ $category->name }}</a>
                            @endforeach
                        </div>
                        <a href="{{ route('blog.show', $article) }}" class="btn btn-primary btn-sm">Read More</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $articles->links() }}
    </div>
@endsection
