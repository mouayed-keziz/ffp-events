<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    /**
     * Download a file from the media library by its ID
     *
     * @param string $id The media item ID
     * @return StreamedResponse The file download response
     */
    public function download($id): StreamedResponse
    {
        $media = Media::find($id);

        if (!$media) {
            abort(404, 'Media not found');
        }

        return $media->toResponse(request());
    }
}
