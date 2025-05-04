<?php

namespace App\Http\Controllers;

use App\Services\BadgeService;
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
            'name' => 'KEZIZ Mouayed',
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
}
