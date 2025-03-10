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
                @livewire('website.share')
            </div>
        </div>
    </div>
</div>
