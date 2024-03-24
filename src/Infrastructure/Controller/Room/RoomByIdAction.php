<?php

namespace App\Infrastructure\Controller\Room;

use App\Domain\Entity\Room;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RoomByIdAction extends AbstractController
{
    #[Route(path: '/rooms/{room}', name: 'app_rooms_by_id', methods: 'GET')]
    public function __invoke(
        Room $room,
        SerializerInterface $serializer,
    ): JsonResponse {
        $data = $serializer->serialize($room, 'json', ['groups' => 'booking_list']);

        return new JsonResponse(
            $data,
            Response::HTTP_OK,
            [],
            true
        );
    }
}
