@props(['events'])
<section class="space-y-6">
    <h2 class="text-2xl font-bold">{{ __('website/home.events.title') }}</h2>

    <div class="space-y-4">
        @foreach ($events as $event)
            @include('website.components.event-card', ['event' => $event])
        @endforeach
    </div>
</section>
