@extends('website.layouts.app')

@section('title', __('website/blog.blog_categories'))

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold">{{ __('website/blog.blog_categories') }}</h1>
        <p class="text-gray-600 mt-2">{{ __('website/blog.browse_by_category') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($categories as $category)
            @include('website.components.category-card', ['category' => $category])
        @endforeach
    </div>
@endsection
