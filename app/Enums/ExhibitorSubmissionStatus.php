<?php

namespace App\Enums;

enum ExhibitorSubmissionStatus: string
{
    case PENDING = 'pending';
    case REJECTED = 'rejected';
    case ACCEPTED = 'accepted';
    case PARTLY_PAYED = 'partly_payed';
    case FULLY_PAYED = 'fully_payed';
    case READY = 'ready';
    case ARCHIVE = 'archive';
}
