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
        $user = Auth("exhibitor")->user();
        $exhibitor_submission = $user ? $user->submissions()->where('event_announcement_id', $event->id)->first() : null;
        $relatedEvents = EventAnnouncement::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        return view('website.pages.events.event', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            "submission" => $exhibitor_submission
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
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if ($exhibitor_submission) {
            return redirect()->route('view_exhibitor_answers', ['id' => $event->id]);
        }
        if ($event->is_exhibitor_registration_open) {
            return view('website.pages.events.exhibit-event', [
                'event' => $event
            ]);
        } else {
            return redirect()->route('events');
        }
    }

    public static function InfoValidation($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['id' => $event->id]);
        }
        return view('website.pages.events.info-validation', [
            'event' => $event
        ]);
    }

    public static function ViewExhibitorAnswers($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['id' => $event->id]);
        }
        return view('website.pages.events.view-exhibitor-answers', [
            'event' => $event,
            "submission" => $exhibitor_submission
        ]);
    }

    public function DownloadInvoice($id) {}
    public function UploadPaymentProof($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['id' => $event->id]);
        }
        return view('website.pages.events.upload-payment-proof', [
            'event' => $event,
            "submission" => $exhibitor_submission
        ]);
    }

    public static function PaymentValidation($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['id' => $event->id]);
        }
        return view('website.pages.events.payment-validation', [
            'event' => $event
        ]);
    }

    public function PostExhibitEvent($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['id' => $event->id]);
        }
        if (!$exhibitor_submission->canFillPostForms) {
            return redirect()->route("event_details", ['id' => $event->id]);
        }
        return view('website.pages.events.post-exhibit-event', [
            'event' => $event,
            "submission" => $exhibitor_submission
        ]);
    }
}
