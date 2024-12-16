<?php

namespace App\Enum;

enum ReservationStatusEnum: string
{
    case CANCELED = 'CANCELED';
    case RESERVED = 'RESERVED';
    case FINISHED = 'FINISHED';
}
