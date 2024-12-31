<?php

namespace App\Models;

use App\Enums\ExportType;
use App\Actions\ExportActions;
use Illuminate\Database\Eloquent\Model;

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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Export $export) {
            ExportActions::delete_files($export);
        });
    }
}
