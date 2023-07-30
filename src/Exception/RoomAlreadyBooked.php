<?php

namespace App\Exception;

use App\Entity\Room;
use Symfony\Component\HttpFoundation\Response;

class RoomAlreadyBooked extends \Exception
{
    public function __construct(Room $room)
    {
        parent::__construct(
            sprintf('The room %s is already booked!', (string) $room->getId()),
            Response::HTTP_BAD_REQUEST
        );
    }
}
