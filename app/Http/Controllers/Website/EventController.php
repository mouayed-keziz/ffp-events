<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\EventAnnouncement;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

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

        $exhibitor_submission = null;
        $visitor_submission = null;

        if (Auth::guard('exhibitor')->check()) {
            $user = Auth::guard('exhibitor')->user();
            $exhibitor_submission = $user->submissions()->where('event_announcement_id', $event->id)->first();
        } elseif (Auth::guard('visitor')->check()) {
            $user = Auth::guard('visitor')->user();
            $visitor_submission = $user->submissions()->where('event_announcement_id', $event->id)->first();
        }

        $relatedEvents = EventAnnouncement::where('id', '!=', $id)->inRandomOrder()->limit(4)->get();
        return view('website.pages.events.event', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            "exhibitorSubmission" => $exhibitor_submission,
            "visitorSubmission" => $visitor_submission
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
        if ($exhibitor_submission->isEditable) {
            return redirect()->route('exhibit_event', ['id' => $event->id]);
        }
        if ($exhibitor_submission->canDownloadInvoice) {
            return redirect()->route('post_exhibit_event', ['id' => $event->id]);
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

    public function DownloadInvoice($id)
    {
        $event = EventAnnouncement::find($id);
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['id' => $event->id]);
        }
        if (!$exhibitor_submission->canDownloadInvoice) {
            return redirect()->route('event_details', ['id' => $event->id]);
        }
        $pdf = Pdf::loadView('pdf.exhibitor-submission-invoice', [
            'event' => $event,
            'exhibitor' => $exhibitor_submission->exhibitor,
            'submission' => $exhibitor_submission
        ]);

        $pdf->setPaper('A4', 'portrait');
        // $pdf->setOption('isRemoteEnabled', true);
        return $pdf->stream('invoice.pdf');

        // return view('pdf.exhibitor-submission-invoice', [
        //     'event' => $event,
        //     'exhibitor' => $exhibitor_submission->exhibitor,
        //     'submission' => $exhibitor_submission
        // ]);
    }
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
        if (!$exhibitor_submission->canFillPostForms || !$event->exhibitorPostPaymentForms) {
            return redirect()->route("event_details", ['id' => $event->id]);
        }
        return view('website.pages.events.post-exhibit-event', [
            'event' => $event,
            "submission" => $exhibitor_submission
        ]);
    }
}
