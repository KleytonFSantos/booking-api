<?php

namespace App\Domain\Enum;

enum ReservationStatusEnum: string
{
    case CANCELED = 'CANCELED';
    case RESERVED = 'RESERVED';
    case FINISHED = 'FINISHED';
}
