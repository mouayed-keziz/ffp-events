@props(['article'])

<div class="relative w-full h-[60vh] min-h-[400px]">
    {{-- Background Image --}}
    <img src="{{ $article->image }}" alt="{{ $article['title'] }}" class="w-full h-full object-cover">

    {{-- Overlay Content --}}
    <div class="absolute inset-0 bg-black/30">
        <div class="container mx-auto h-full max-w-6xl relative px-4">
            {{-- Return Link and Title --}}
            <div class="absolute top-12 left-4 space-y-4">
                <a href="{{ route('events') }}"
                    class="flex items-center gap-2 text-white hover:text-primary transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                    Retourner
                </a>
                <h1 class="text-white text-2xl md:text-4xl font-bold max-w-2xl">{{ $article['title'] }}</h1>
            </div>

            {{-- Bottom Container --}}
            <div class="absolute bottom-8 left-4 right-4 flex gap-4 items-center">
                {{-- Countdown --}}
                <div class="flex-1">
                   {{-- Circular Logo with App Name and Created Date --}}
                   <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-white flex items-center justify-center">
                        @include('website.components.brand.logo-no-text')
                    </div>
                    <div class="text-white space-y-1">
                        <div class="font-bold text-sm">{{ config('app.name') }}</div>
                        <div class="text-xs">{{ $article->created_at->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
                {{-- Share Button --}}
                <button class="btn btn-primary btn-circle btn-md md:btn-lg shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 md:w-6 md:h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
