@props(['events'])
<section class="space-y-6">
    <h2 class="text-2xl font-bold">{{ __('website/home.events.title') }}</h2>

    <div class="space-y-4">
        @if (count($events) > 0)
            @foreach ($events as $event)
                @include('website.components.event-card', ['event' => $event])
            @endforeach
        @else
            <p class="text-center text-gray-500">{{ __('website/home.no_events') }}</p>
        @endif
    </div>
</section>
