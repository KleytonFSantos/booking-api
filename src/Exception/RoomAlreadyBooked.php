<?php

namespace App\Exception;

use App\Entity\Room;
use Symfony\Component\HttpFoundation\Response;

class RoomAlreadyBooked extends \Exception
{
    public function __construct(
        private readonly ?Room $room
    ) {
        parent::__construct(
            $this->errorMessage(),
            Response::HTTP_BAD_REQUEST
        );
    }

    public function errorMessage(): string
    {
        if (empty($this->room)) {
            return 'No room available';
        }

        return sprintf(
            'The room %s is already booked!',
            $this->room->getRoomNumber()
        );
    }
}
