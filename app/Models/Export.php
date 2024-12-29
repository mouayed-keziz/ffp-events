<?php

namespace App\Models;

use App\Enums\ExportType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Export extends Model
{
    protected $fillable = [
        'completed_at',
        'file_disk',
        'file_name',
        'exporter',
        'processed_rows',
        'total_rows',
        'successful_rows',
        'user_id'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        "exporter" => ExportType::class
    ];

    public function exported_by()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function delete_files()
    {
        $disk = Storage::disk($this->file_disk);
        $dirs = $disk->directories('filament_exports');
        foreach ($dirs as $dir) {
            if ($dir !== "filament_exports/{$this->id}") {
                continue;
            }
            $files = $disk->files($dir);
            foreach ($files as $file) {
                $disk->delete($file);
            }
            $disk->deleteDirectory($dir);
        }
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Export $export) {
            $export->delete_files();
        });
    }
}
