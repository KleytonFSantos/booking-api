<?php

namespace App\Infrastructure\Service;

use App\Domain\DTO\RoomDTO;
use App\Domain\Entity\Room;
use App\Domain\Repository\RoomRepository;

readonly class RoomService
{

    public function __construct(
        private RoomRepository $roomRepository
    )
    {
    }

    public function createRoom(RoomDTO $roomDTO): void
    {
        $room = new Room();
        $room->setRoomNumber($roomDTO->getRoomNumber());
        $room->setPrice($roomDTO->getPrice());
        $room->setVacancy($roomDTO->isVacancy());

        $this->roomRepository->save($room);
    }

    public function updateRoom(RoomDTO $roomDTO, Room $room): void
    {
        $room->setRoomNumber($roomDTO->getRoomNumber());
        $room->setPrice($roomDTO->getPrice());

        $this->roomRepository->save($room);
    }
}