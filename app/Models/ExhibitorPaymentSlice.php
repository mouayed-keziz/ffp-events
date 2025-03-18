<?php

namespace App\Models;

use App\Enums\PaymentSliceStatus;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExhibitorPaymentSlice extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'exhibitor_submission_id',
        'sort',
        'price',
        'status',
        'currency',
        'due_to'
    ];
    protected $casts = [
        'status' => PaymentSliceStatus::class,
        'due_to' => 'datetime',
    ];

    public function exhibitorSubmission()
    {
        return $this->belongsTo(ExhibitorSubmission::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachement')
            ->singleFile();
    }

    public function attachMedia($file)
    {
        $this->addMedia($file)->toMediaCollection('attachement');
    }
}
