<?php

namespace App\Infrastructure\Controller\Room;

use App\Domain\Entity\Room;
use App\Domain\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomDeleteAction extends AbstractController
{
    #[Route(path: '/rooms/{room}', name: 'app_rooms_delete', methods: 'DELETE')]
    public function __invoke(
        Room $room,
        RoomRepository $roomRepository,
    ): JsonResponse {
        $roomRepository->remove($room);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
}
