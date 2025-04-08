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
        'properties' => 'array',
    ];

    public function getSubjectFieldAttribute()
    {
        if ($this->log_name === LogName::Authentication) {
            return null;
        }
        if ($this->subject === null) {
            return __('panel/logs.empty_states.deleted_record') . ' - id:' . $this->subject_id;
        }
        return $this->subject->recordTitle;
    }
}
