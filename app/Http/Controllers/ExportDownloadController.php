<?php

namespace App\Http\Controllers;

use App\Models\Export;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\Http\Controllers\DownloadExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportDownloadController extends Controller
{
    public function download(Request $request, Export $export, ExportFormat $format): StreamedResponse
    {

        Gate::authorize('download', $export);
        // Generate file path based on the format
        $baseFilename = pathinfo($export->file_name, PATHINFO_FILENAME);
        $fileExtension = strtolower($format->value);
        if ($fileExtension === "csv") {
            $filePath = "filament_exports/{$export->id}/0000000000000001.{$fileExtension}";
        } else {
            $filePath = "filament_exports/{$export->id}/{$baseFilename}.{$fileExtension}";
        }

        // Check if the file exists
        if (!Storage::disk($export->file_disk)->exists($filePath)) {
            abort(404, 'Export file not found.');
        }

        // Return the file as a download
        return Storage::disk($export->file_disk)->download(
            $filePath,
            "{$baseFilename}.{$fileExtension}",
        );
    }
}
