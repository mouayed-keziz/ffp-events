@props(['events'])

@php
    use Illuminate\Support\Facades\Auth;

    $visitorSubmissions = [];
    $exhibitorSubmissions = [];

    if (Auth::guard('visitor')->check()) {
        $visitorSubmissions = Auth::guard('visitor')
            ->user()
            ->submissions()
            ->whereIn('event_announcement_id', $events->pluck('id'))
            ->get()
            ->keyBy('event_announcement_id')
            ->toArray();
    }

    if (Auth::guard('exhibitor')->check()) {
        $exhibitorSubmissions = Auth::guard('exhibitor')
            ->user()
            ->submissions()
            ->whereIn('event_announcement_id', $events->pluck('id'))
            ->get()
            ->keyBy('event_announcement_id')
            ->toArray();
    }
@endphp

<section class="space-y-6">
    <h2 class="text-2xl font-bold">{{ __('website/home.events.title') }}</h2>

    <div class="space-y-4">
        @if (count($events) > 0)
            @foreach ($events as $event)
                @include('website.components.event-card', [
                    'event' => $event,
                    'visitorSubmission' => $visitorSubmissions[$event->id] ?? null,
                    'exhibitorSubmission' => $exhibitorSubmissions[$event->id] ?? null,
                ])
            @endforeach
        @else
            <p class="text-center text-gray-500">{{ __('website/home.no_events') }}</p>
        @endif
    </div>
</section>
