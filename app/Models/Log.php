<?php

namespace App\Models;

use App\Enums\LogEvent;
use App\Enums\LogName;
use Spatie\Activitylog\Models\Activity;

class Log extends Activity
{
    protected $casts = [
        'event' => LogEvent::class,
        'log_name' => LogName::class,
    ];
}
