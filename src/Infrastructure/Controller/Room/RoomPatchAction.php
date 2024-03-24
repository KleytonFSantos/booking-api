<?php

namespace App\Infrastructure\Controller\Room;

use App\Domain\DTO\RoomUpdateRequestDTO;
use App\Domain\Entity\Room;
use App\Infrastructure\Service\RoomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RoomPatchAction extends AbstractController
{

    public function __construct(
        private readonly RoomService $roomService
    ) {
    }

    #[Route(path: '/rooms/{room}', name: 'app_rooms_update', methods: 'PATCH')]
    public function __invoke(
        Request             $request,
        Room                $room,
        SerializerInterface $serializer,
    ): JsonResponse
    {
        $data = $serializer->deserialize(
            $request->getContent(),
            RoomUpdateRequestDTO::class,
            'json'
        );

        $this->roomService->updateRoom($data, $room);

        $dataUpdated = $serializer->serialize($room, 'json', ['groups' => 'booking_list']);

        return new JsonResponse($dataUpdated, Response::HTTP_OK, [], true);
    }
}