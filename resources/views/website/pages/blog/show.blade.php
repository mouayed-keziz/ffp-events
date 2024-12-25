@extends('website.layouts.app')

@section('title', $article->title)

@section('content')
    <article class="min-h-screen">
        {{-- Hero Section --}}
        <div class="relative">
            @if ($article->hasMedia('image'))
                <div class="w-full h-[60vh] relative rounded-box border border-base-200 overflow-hidden shadow-xl">
                    <img src="{{ $article->getFirstMediaUrl('image') }}" alt="{{ $article->title }}"
                        class="w-full h-full object-cover brightness-[0.8]" />
                    {{-- Enhanced gradient overlay for better contrast --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-75"></div>
                </div>
            @endif

            <div
                class="container mx-auto px-4 {{ $article->hasMedia('image') ? 'absolute bottom-8 left-0 right-0' : 'pt-12' }}">
                <div class="max-w-4xl mx-auto">
                    {{-- Categories with conditional styling --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach ($article->categories as $category)
                            <a href="{{ route('blog.category', $category) }}"
                                class="badge transition-colors
                                {{ $article->hasMedia('image')
                                    ? 'bg-primary/10 text-white hover:bg-primary hover:text-white'
                                    : 'bg-primary/10 text-primary hover:bg-primary hover:text-white' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Title with conditional styling --}}
                    <h1
                        class="text-4xl md:text-5xl font-bold mb-6 {{ $article->hasMedia('image') ? 'text-white drop-shadow-lg' : 'text-base-content' }}">
                        {{ $article->title }}
                    </h1>

                    {{-- Metadata with conditional styling --}}
                    <div
                        class="flex items-center gap-6 text-sm {{ $article->hasMedia('image') ? 'text-white/90' : 'text-base-content/70' }} mb-8">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-calendar class="w-5 h-5" />
                            <span>{{ $article->published_at->format('F j, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-eye class="w-5 h-5" />
                            <span>{{ $viewCount }} views</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="container mx-auto px-4 py-12">
            <div class="max-w-4xl mx-auto">
                {{-- Main Content --}}
                <div
                    class="prose prose-lg max-w-none prose-headings:text-primary prose-a:text-secondary hover:prose-a:text-secondary-focus prose-img:rounded-xl">
                    {!! str($article->content)->sanitizeHtml() !!}
                </div>

                {{-- Back Button --}}
                <div class="mt-16 pt-8 border-t border-base-300">
                    <a href="{{ route('blog.index') }}" class="btn btn-primary btn-outline gap-2">
                        <x-heroicon-o-arrow-left class="w-5 h-5" />
                        Back to Blog
                    </a>
                </div>
            </div>
        </div>
    </article>
@endsection
