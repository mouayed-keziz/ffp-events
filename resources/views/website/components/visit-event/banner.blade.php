@props(['event'])


<div class="relative w-full h-[60vh] min-h-[400px]">
    <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/30">
        <div class="container mx-auto h-full max-w-5xl relative px-4">
            <!-- Go Back Link and Title Container -->
            <div class="flex flex-col justify-start items-start py-12"
                class="absolute top-12 left-4 right-4 flex items-center @if (app()->getLocale() === 'ar') flex-row-reverse @endif">
                <a href="{{ route('event_details', $event) }}"
                    class="flex items-center gap-2 text-white hover:text-primary transition-colors">
                    @if (app()->getLocale() === 'ar')
                        @include('website.svg.event.chevron-right')
                    @else
                        @include('website.svg.event.chevron-left')
                    @endif
                    {{ __('website/visit-event.goback') }}
                </a>
                <h1 class="text-white text-2xl md:text-4xl font-bold max-w-2xl mt-24">
                    {{ $event['title'] }}
                </h1>
                <div
                    class="bg-black/10 backdrop-blur-md text-white text-sm px-3 py-1 rounded flex items-center gap-2 mt-2">
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
