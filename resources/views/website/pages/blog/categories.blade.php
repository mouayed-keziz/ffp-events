@extends('website.layouts.app')

@section('title', 'Categories')

@section('content')
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold">Blog Categories</h1>
        <p class="text-gray-600 mt-2">Browse articles by category</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('blog.category', $category) }}" class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow">
                <div class="card-body">
                    <h2 class="card-title">{{ $category->name }}</h2>
                    <p class="text-gray-600">{{ $category->articles_count }} articles</p>
                </div>
            </a>
        @endforeach
    </div>
@endsection
