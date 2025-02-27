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
        if (!$event) {
            return redirect()->route('events');
        }
        $relatedEvents = EventAnnouncement::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        return view('website.pages.events.event', [
            'event' => $event,
            'relatedEvents' => $relatedEvents
        ]);
    }

    public static function VisitEvent($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        if ($event->is_visitor_registration_open) {
            return view('website.pages.events.visit-event', [
                'event' => $event
            ]);
        } else {
            return redirect()->route('events');
        }
    }

    public static function ExhibitEvent($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        if ($event->is_exhibitor_registration_open) {
            return view('website.pages.events.exhibit-event', [
                'event' => $event
            ]);
        } else {
            return redirect()->route('events');
        }
    }
}
