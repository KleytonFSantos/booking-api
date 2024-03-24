<?php

namespace App\Domain\Enum;

enum RatingEnum: int
{
    case VERY_BAD = 1;
    case BAD = 2;
    case REGULAR = 3;
    case GOOD = 4;
    case VERY_GOOD = 5;
}
