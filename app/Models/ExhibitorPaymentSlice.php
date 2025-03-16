<?php

namespace App\Models;

use App\Enums\PaymentSliceStatus;
use Illuminate\Database\Eloquent\Model;

class ExhibitorPaymentSlice extends Model
{
    protected $fillable = [
        'exhibitor_submission_id',
        'sort',
        'price',
        'status',
        'currency',
    ];

    protected $casts = [
        'status' => PaymentSliceStatus::class,
    ];

    public function exhibitorSubmission()
    {
        return $this->belongsTo(ExhibitorSubmission::class);
    }

    public function attachMedia($file)
    {
        $this->addMedia($file)->toMediaCollection('attachement');
    }
}
