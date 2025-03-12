@props(['article'])

<div class="relative w-full h-[60vh] min-h-[400px]">
    {{-- Background Image --}}
    <img src="{{ $article->image }}" alt="{{ $article['title'] }}" class="w-full h-full object-cover">

    {{-- Overlay Content --}}
    <div class="absolute inset-0 bg-black/30">
        <div class="container mx-auto h-full max-w-6xl relative px-4">
            {{-- Return Link and Title --}}
            <!-- Go Back Link and Title Container -->
            <div class="flex flex-col justify-start items-start py-12"
                class="absolute top-12 left-4 right-4 flex items-center @if (app()->getLocale() === 'ar') flex-row-reverse @endif">
                <a href="{{ route('articles') }}"
                    class="flex items-center gap-2 text-white hover:text-primary transition-colors">
                    @if (app()->getLocale() === 'ar')
                        @include('website.svg.event.chevron-right')
                    @else
                        @include('website.svg.event.chevron-left')
                    @endif
                    {{ __('website/event.goback') }}
                </a>
                <h1 class="text-white text-2xl md:text-4xl font-bold max-w-2xl mt-8">
                    {{ $article['title'] }}
                </h1>
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
                            <div class="text-xs">
                                {{ \Carbon\Carbon::parse($article->published_at)->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>
                </div>
                {{-- Share Button --}}
                @livewire('website.share', [
                    'title' => $article->title,
                    'description' => $article->description,
                    'url' => route('article', $article),
                    'model' => $article,
                ])
            </div>
        </div>
    </div>
</div>
