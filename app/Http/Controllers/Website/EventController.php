<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\EventAnnouncement;

class EventController extends Controller
{
    public static function Events()
    {
        $events = EventAnnouncement::orderBy('created_at', 'desc')->limit(10)->get();
        return view('website.pages.events.events', [
            'events' => $events
        ]);
    }

    public static function Event($id)
    {
        $event = EventAnnouncement::find($id);
        $relatedEvents = EventAnnouncement::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        if (!$event) {
            return redirect()->route('events');
        }
        return view('website.pages.events.event', [
            'event' => $event,
            'relatedEvents' => $relatedEvents
        ]);
    }

    public static function EventVisitorRegistration($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }

        $now = \Carbon\Carbon::now();
        if ($now->lt($event->visitor_registration_start_date) || $now->gt($event->visitor_registration_end_date)) {
            return redirect()->route('events');
        }

        return view('website.pages.events.visit-event', [
            'event' => $event
        ]);
    }
}
