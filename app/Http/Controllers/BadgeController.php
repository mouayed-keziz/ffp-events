<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
use App\Models\EventAnnouncement;
use App\Models\ExhibitorSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BadgeController extends Controller
{
    /**
     * Display a generated badge preview.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request)
    {
        $eventId = $request->query('event_id');
        $badgeType = $request->query('badge_type'); // e.g., 'visitor', 'exhibitor', 'sponsor'
        // dd($eventId, $badgeType);
        if (!$eventId || !$badgeType) {
            Log::warning('Badge preview request missing parameters.', ['event_id' => $eventId, 'badge_type' => $badgeType]);
            return Response::make('Missing required parameters: event_id and badge_type', 400);
        }

        // Get the path to the stored template
        $templatePath = BadgeService::getTemplatePath((int)$eventId, $badgeType);

        if (!$templatePath || !file_exists($templatePath)) {
            Log::error('Badge template not found.', ['event_id' => $eventId, 'badge_type' => $badgeType, 'path_checked' => $templatePath]);
            return Response::make('Badge template not found for the specified event and type.', 404);
        }

        // Prepare dummy data
        $dummyData = [
            'name' => 'Keziz Mouayed',
            'job' => 'Software Engineer',
            'company' => 'FFP-events',
            'qr_data' => 'EVENT:' . $eventId . ';TYPE:' . $badgeType . ';UID:' . Str::uuid(), // Example QR data
        ];

        // Generate the badge image using the service
        $generatedImage = BadgeService::generateBadgePreview($templatePath, $dummyData);

        if (!$generatedImage) {
            return Response::make('Failed to generate badge preview.', 500);
        }

        try {
            // Encode the generated image to PNG format
            $imageData = $generatedImage->toPng(); // Or ->toJpeg()

            // Return the image as a response
            return Response::make($imageData)
                ->header('Content-Type', 'image/png'); // Adjust content type if using JPEG

        } catch (\Exception $e) {
            Log::error('Failed to encode generated badge image.', [
                'event_id' => $eventId,
                'badge_type' => $badgeType,
                'error' => $e->getMessage()
            ]);
            return Response::make('Failed to encode badge image.', 500);
        }
    }

    /**
     * Display a generated badge preview placed on a blank A4 and cropped to top-left quarter.
     * Optional query params: name, job, company, qr_data (all optional; defaults applied).
     */
    public function showBlank(Request $request)
    {
        $data = [
            'name' => $request->query('name', 'Sample Name'),
            'job' => $request->query('job', 'Sample Job Title'),
            'company' => $request->query('company', 'Sample Company'),
            'qr_data' => $request->query('qr_data', 'SAMPLE:' . Str::uuid()),
        ];

        $generatedImage = BadgeService::generateBadgePreviewOnBlank($data);

        if (!$generatedImage) {
            return Response::make('Failed to generate blank badge preview.', 500);
        }

        try {
            $imageData = $generatedImage->toPng();
            return Response::make($imageData)->header('Content-Type', 'image/png');
        } catch (\Exception $e) {
            Log::error('Failed to encode blank badge preview image.', [
                'error' => $e->getMessage(),
            ]);
            return Response::make('Failed to encode blank badge image.', 500);
        }
    }

    /**
     * Download badges zip file
     *
     * @param Request $request
     * @param EventAnnouncement $event
     * @param ExhibitorSubmission $submission
     * @param string $zipPath
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadBadgesZip(Request $request, EventAnnouncement $event, ExhibitorSubmission $submission, $zipPath)
    {
        // Check if user has permission
        if (!auth('exhibitor')->check() || auth('exhibitor')->id() !== $submission->exhibitor_id) {
            abort(403, 'Unauthorized');
        }

        // Validate that the submission belongs to the event
        if ($submission->event_announcement_id !== $event->id) {
            abort(404, 'Submission not found for this event');
        }

        $fullPath = storage_path('app/temp/' . $zipPath);

        // Check if file exists
        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }        // Get redirection URL from request if available
        $redirectUrl = $request->query('redirect_to');

        // Set flash message for after download redirection
        if ($redirectUrl) {
            session()->flash('success', __('website/manage-badges.badges_download_success'));

            // Store the redirect URL in session instead of cookie
            session(['badge_download_redirect' => $redirectUrl]);
        }

        // Download the file
        return response()->download($fullPath, "badges_{$event->title}_{$submission->exhibitor->name}.zip", [
            'Content-Type' => 'application/zip'
        ])->deleteFileAfterSend();
    }
}
