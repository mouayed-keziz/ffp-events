@extends('website.layouts.app')

@section('content')
    <main class="w-full max-w-7xl mx-auto px-4 py-8 flex-grow">
        <a class="btn btn-primary" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
            target="_blank">
            Share on Facebook
        </a>

        {{-- @livewire('website.articles.articles-page') --}}
    </main>
@endsection
