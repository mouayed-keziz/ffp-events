@extends('website.layouts.app')

@section('title', __('website/blog.our_blog'))

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold">{{ __('website/blog.our_blog') }}</h1>
        <p class="text-gray-600 mt-2">{{ __('website/blog.discover_articles') }}</p>
    </div>

    <div class="flex justify-center gap-8 mb-12">
        <div class="stats shadow border">
            <div class="stat">
                <div class="stat-figure text-primary">
                    <x-heroicon-o-eye class="w-8 h-8" />
                </div>
                <div class="stat-title">{{ __('website/blog.total_views') }}</div>
                <div class="stat-value text-primary">{{ $totalViews }}</div>
                <div class="stat-desc">{{ __('website/blog.more_than_last_month') }}</div>
            </div>

            <div class="stat">
                <div class="stat-figure text-secondary">
                    <x-heroicon-o-book-open class="w-8 h-8" />
                </div>
                <div class="stat-title">{{ __('website/blog.total_articles') }}</div>
                <div class="stat-value text-secondary">{{ $articles->total() }}</div>
                <div class="stat-desc">{{ __('website/blog.growing_weekly') }}</div>
            </div>
        </div>
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
