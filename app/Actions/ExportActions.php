<?php

namespace App\Actions;

use Illuminate\Support\Facades\Storage;
use App\Models\Export;

class ExportActions
{
    // called when an Export is deleted, delete the expoerted files 
    public static function delete_files(Export $export): void
    {
        $disk = Storage::disk($export->file_disk);
        $dirs = $disk->directories('filament_exports');
        foreach ($dirs as $dir) {
            if ($dir !== "filament_exports/{$export->id}") {
                continue;
            }
            $files = $disk->files($dir);
            foreach ($files as $file) {
                $disk->delete($file);
            }
            $disk->deleteDirectory($dir);
        }
    }
}
