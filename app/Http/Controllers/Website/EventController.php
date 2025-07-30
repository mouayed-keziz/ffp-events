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

    public static function Event($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
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

        $relatedEvents = EventAnnouncement::where('id', '!=', $event->id)->inRandomOrder()->limit(4)->get();
        return view('website.pages.events.event', [
            'event' => $event,
            'relatedEvents' => $relatedEvents,
            "exhibitorSubmission" => $exhibitor_submission,
            "visitorSubmission" => $visitor_submission
        ]);
    }

    public static function VisitEvent($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $visitorSubmission = Auth('visitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();

        if ($visitorSubmission) {
            return redirect()->route('visit_event_form_submitted', ['slug' => $event->slug]);
        }
        if ($event->is_visitor_registration_open) {
            return view('website.pages.events.visit-event', [
                'event' => $event
            ]);
        } else {
            return redirect()->route('events');
        }
    }

    public static function VisitEventAnonymous($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }

        if ($event->is_visitor_registration_open) {
            return view('website.pages.events.visit-event-anonymous', [
                'event' => $event
            ]);
        } else {
            dd("here");
            return redirect()->route('events');
        }
    }

    public function VisitFormSubmitted($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $visitorSubmission = Auth('visitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$visitorSubmission) {
            return redirect()->route('visit_event', ['slug' => $event->slug]);
        }
        return view("website.pages.events.visit-event-form-submitted", [
            'event' => $event,
            'submission' => $visitorSubmission
        ]);
    }

    public function VisitAnonymousFormSubmitted($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }

        return view("website.pages.events.visit-event-anonymous-form-submitted", [
            'event' => $event
        ]);
    }

    public static function ExhibitEvent($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if ($exhibitor_submission) {
            return redirect()->route('view_exhibitor_answers', ['slug' => $event->slug]);
        }
        if ($event->is_exhibitor_registration_open) {
            return view('website.pages.events.exhibit-event', [
                'event' => $event
            ]);
        } else {
            return redirect()->route('events');
        }
    }

    public static function InfoValidation($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }
        if ($exhibitor_submission->isEditable) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }
        if ($exhibitor_submission->canDownloadInvoice) {
            return redirect()->route('post_exhibit_event', ['slug' => $event->slug]);
        }
        return view('website.pages.events.info-validation', [
            'event' => $event
        ]);
    }

    public static function ViewExhibitorAnswers($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }
        return view('website.pages.events.view-exhibitor-answers', [
            'event' => $event,
            "submission" => $exhibitor_submission
        ]);
    }

    public function DownloadInvoice($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }
        if (!$exhibitor_submission->canDownloadInvoice) {
            return redirect()->route('event_details', ['slug' => $event->slug]);
        }

        // Log invoice download activity
        $user = Auth('exhibitor')->user();
        \App\Activity\ExhibitorSubmissionActivity::logInvoiceDownload($user, $exhibitor_submission);

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
    public function UploadPaymentProof($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }
        return view('website.pages.events.upload-payment-proof', [
            'event' => $event,
            "submission" => $exhibitor_submission
        ]);
    }

    public static function PaymentValidation($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }
        return view('website.pages.events.payment-validation', [
            'event' => $event
        ]);
    }

    public function PostExhibitEvent($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }
        if (!$exhibitor_submission->canFillPostForms || !$event->exhibitorPostPaymentForms) {
            return redirect()->route("event_details", ['slug' => $event->slug]);
        }
        return view('website.pages.events.post-exhibit-event', [
            'event' => $event,
            "submission" => $exhibitor_submission
        ]);
    }

    public static function TermsAndConditions($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }
        return view('website.pages.events.terms-and-conditions', [
            'event' => $event
        ]);
    }

    public function DownloadVisitorBadge($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }

        // Get visitor submission
        $visitorSubmission = Auth('visitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$visitorSubmission) {
            return redirect()->route('visit_event', ['slug' => $event->slug]);
        }

        // Check if the submission has a badge
        $badge = $visitorSubmission->badge;
        if (!$badge) {
            return redirect()->back()->with('error', __('website/visit-event.badge_not_found'));
        }

        // Get the badge image
        $badgeMedia = $badge->getFirstMedia('image');
        if (!$badgeMedia || !file_exists($badgeMedia->getPath())) {
            return redirect()->back()->with('error', __('website/visit-event.badge_image_not_found'));
        }

        // Get the event name in French
        $eventName = $event->getTranslation('title', 'fr', false);

        // Get the visitor's name
        $visitorName = $badge->name;

        // Create the filename in the required format: {event_name in fr} - {nom}.png
        $filename = $eventName . ' - ' . $visitorName . '.png';

        // Return the badge image for download
        return response()->download(
            $badgeMedia->getPath(),
            $filename,
            ['Content-Type' => 'image/png']
        );
    }

    public function ManageExhibitorBadges($slug)
    {
        $event = EventAnnouncement::where('slug', $slug)->first();
        if (!$event) {
            return redirect()->route('events');
        }

        $exhibitor_submission = Auth('exhibitor')->user()->submissions()->where('event_announcement_id', $event->id)->first();
        if (!$exhibitor_submission) {
            return redirect()->route('exhibit_event', ['slug' => $event->slug]);
        }

        // Check if submission status is partly paid or fully paid
        if (!($exhibitor_submission->status === \App\Enums\ExhibitorSubmissionStatus::PARTLY_PAYED ||
            $exhibitor_submission->status === \App\Enums\ExhibitorSubmissionStatus::FULLY_PAYED)) {
            return redirect()->route('event_details', ['slug' => $event->slug]);
        }

        return view('website.pages.events.manage-exhibitor-badges', [
            'event' => $event,
            'submission' => $exhibitor_submission
        ]);
    }
}
