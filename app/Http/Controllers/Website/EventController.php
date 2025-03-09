<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\EventAnnouncement;
use Illuminate\Support\Facades\Auth;

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
        $visitorSubmission = Auth('visitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();

        if ($visitorSubmission) {
            return redirect()->route('visit_event_form_submitted', ['id' => $event->id]);
        }
        if ($event->is_visitor_registration_open) {
            return view('website.pages.events.visit-event', [
                'event' => $event
            ]);
        } else {
            return redirect()->route('events');
        }
    }

    public function VisitFormSubmitted($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        $visitorSubmission = Auth('visitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        // dd($visitorSubmission);
        if (!$visitorSubmission) {
            return redirect()->route('visit_event', ['id' => $event->id]);
        }
        return view("website.pages.events.visit-event-form-submitted", [
            'event' => $event
        ]);
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
