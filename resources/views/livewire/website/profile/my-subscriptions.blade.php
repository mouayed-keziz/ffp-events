<?php

use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\EventAnnouncement;

new class extends Component {
    public $events = [];
    public $visitorSubmissions = [];
    public $exhibitorSubmissions = [];

    public function mount(): void
    {
        // Get submissions based on guard
        if (Auth::guard('visitor')->check()) {
            $visitor = Auth::guard('visitor')->user();
            $submissions = $visitor->submissions()->with('eventAnnouncement')->get();

            // Get unique events from submissions
            $eventIds = $submissions->pluck('event_announcement_id')->unique()->toArray();
            $this->events = EventAnnouncement::whereIn('id', $eventIds)->orderBy('created_at', 'desc')->get();

            // Group submissions by event ID
            $this->visitorSubmissions = $submissions->keyBy('event_announcement_id')->toArray();
        } elseif (Auth::guard('exhibitor')->check()) {
            $exhibitor = Auth::guard('exhibitor')->user();
            $submissions = $exhibitor->submissions()->with('eventAnnouncement')->get();

            // Get unique events from submissions
            $eventIds = $submissions->pluck('event_announcement_id')->unique()->toArray();
            $this->events = EventAnnouncement::whereIn('id', $eventIds)->orderBy('created_at', 'desc')->get();

            // Group submissions by event ID
            $this->exhibitorSubmissions = $submissions->keyBy('event_announcement_id')->toArray();
        } elseif (Auth::guard('web')->check()) {
            // Redirect admins to the admin interface
            redirect('/admin');
        }
    }
}; ?>

<section class="space-y-6">
    <h2 class="text-2xl font-bold">{{ __('website/profile.my_subscriptions') }}</h2>

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
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <div class="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="stroke-info shrink-0 w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>You don't have any subscriptions yet.</span>
                </div>
            </div>
        @endif
    </div>
</section>
