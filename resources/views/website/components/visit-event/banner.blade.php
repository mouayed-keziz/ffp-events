@props(['event'])

@php
    // Calculate dynamic height based on title length
    $titleLength = strlen($event['title']);
    $estimatedLines = ceil($titleLength / 50); // Rough estimation: 50 chars per line

    // Base height classes
    $heightClass = 'h-[60vh] min-h-[400px]';

    // Increase height if title is likely to wrap multiple lines
    if ($estimatedLines > 2) {
        $heightClass = 'h-[70vh] min-h-[500px]';
    } elseif ($estimatedLines > 1) {
        $heightClass = 'h-[65vh] min-h-[450px]';
    }
@endphp

<div class="relative w-full {{ $heightClass }}">
    <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/30">
        <div class="container mx-auto h-full max-w-5xl relative px-4">
            <!-- Go Back Link and Title Container -->
            <div class="flex flex-col justify-between h-full py-8">
                <div class="flex flex-col">
                    <a href="{{ route('event_details', $event) }}"
                        class="flex items-center gap-2 text-white hover:text-primary transition-colors mb-8 @if (app()->getLocale() === 'ar') flex-row-reverse @endif">
                        @if (app()->getLocale() === 'ar')
                            @include('website.svg.event.chevron-right')
                        @else
                            @include('website.svg.event.chevron-left')
                        @endif
                        {{ __('website/visit-event.goback') }}
                    </a>
                    <h1 class=" mt-24 text-white text-2xl md:text-4xl font-bold leading-tight max-w-4xl">
                        {{ $event['title'] }}
                    </h1>
                    <div
                        class="bg-white/15 backdrop-blur-md text-white text-sm px-3 py-1 rounded flex items-center gap-2 mt-4 w-fit">
                        @include('website.svg.event.callendar-white')
                        <span>{{ __('website/visit-event.visitor_registration_end_date') }} :
                            <span>
                                {{ \Carbon\Carbon::parse($event['visitor_registration_end_date'])->locale(app()->getLocale())->translatedFormat('j F Y') }}
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
