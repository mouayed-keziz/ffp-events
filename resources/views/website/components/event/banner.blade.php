@props(['event'])

<div class="relative w-full h-[60vh] min-h-[400px]">
    <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-black/30">
        <div class="container mx-auto h-full max-w-5xl relative px-4">
            <!-- Go Back Link and Title Container -->
            <div class="flex flex-col justify-start items-start py-12"
                class="absolute top-12 left-4 right-4 flex items-center @if (app()->getLocale() === 'ar') flex-row-reverse @endif">
                <a href="{{ route('events') }}"
                    class="flex items-center gap-2 text-white hover:text-primary transition-colors">
                    @if (app()->getLocale() === 'ar')
                        @include('website.svg.event.chevron-right')
                    @else
                        @include('website.svg.event.chevron-left')
                    @endif
                    {{ __('website/event.goback') }}
                </a>
                <h1 class="text-white text-2xl md:text-4xl font-bold max-w-2xl mt-8">
                    {{ $event['title'] }}
                </h1>
            </div>
            <!-- Bottom Container -->
            <div class="absolute bottom-8 left-4 right-4 flex gap-4 items-center">
                <div class="flex-1">
                    @if ($event['countdown']['is_past'])
                        <div class="text-sm font-bold text-white bg-red-500 rounded-full px-3 py-1 w-fit">
                            {{ __('website/event.is_past') }}
                        </div>
                    @elseif ($event['countdown']['is_ongoing'])
                        @include('website.components.countdown', [
                            'countdown' => $event['countdown']['diff'],
                            'size' => 'lg',
                        ])
                    @else
                        @include('website.components.countdown', [
                            'countdown' => $event['countdown']['diff'],
                            'size' => 'lg',
                        ])
                    @endif
                </div>
                @livewire('website.share', [
                    'title' => $event->title,
                    'description' => $event->description,
                    'url' => route('event_details', $event),
                    'model' => $event,
                ])
            </div>
        </div>
    </div>
</div>
