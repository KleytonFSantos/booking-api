<?php

namespace App\Infrastructure\Controller\Room;

use App\Domain\DTO\RoomDTO;
use App\Infrastructure\Service\RoomService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RoomPostAction extends AbstractController
{
    public function __construct(
        private readonly RoomService $roomService
    ) {
    }

    #[Route(path: '/rooms', name: 'app_rooms_create', methods: 'POST')]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
    ): JsonResponse {
        $data = $serializer->deserialize(
            $request->getContent(),
            RoomDTO::class,
            'json'
        );

        $this->roomService->createRoom($data);

        return new JsonResponse('', Response::HTTP_CREATED);
    }
}
