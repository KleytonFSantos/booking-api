<?php

namespace App\Infrastructure\Controller\Room;

use App\Domain\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RoomGetAction extends AbstractController
{
    #[Route(path: '/rooms', name: 'app_rooms', methods: 'GET')]
    public function __invoke(
        RoomRepository $roomRepository,
        SerializerInterface $serializer,
    ): JsonResponse {
        $allRooms = $serializer->serialize($roomRepository->findAllOrderedByRoomNumber(), 'json', ['groups' => 'booking_list']);

        return new JsonResponse($allRooms, Response::HTTP_OK, [], true);
    }
}
