<?php

namespace App\Controller\Customer;

use App\DTO\RoomDTO;
use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class RoomController extends AbstractController
{
    #[Route(path: '/rooms', name: 'app_rooms', methods: 'GET')]
    public function index(
        RoomRepository $roomRepository,
        SerializerInterface $serializer,
    ): JsonResponse {
        $allRooms = $serializer->serialize($roomRepository->findAllOrderedByRoomNumber(), 'json', ['groups' => 'booking_list']);

        return new JsonResponse($allRooms, Response::HTTP_OK, [], true);
    }

    #[Route(path: '/rooms/{room}', name: 'app_rooms_delete', methods: 'DELETE')]
    public function delete(
        Room $room,
        RoomRepository $roomRepository,
    ): JsonResponse {
        $roomRepository->remove($room);

        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/rooms', name: 'app_rooms_create', methods: 'POST')]
    public function create(
        Request $request,
        RoomRepository $roomRepository,
        SerializerInterface $serializer,
    ): JsonResponse {
        $data = $serializer->deserialize($request->getContent(), RoomDTO::class, 'json');

        $room = new Room();
        $room->setRoomNumber($data->getRoomNumber());
        $room->setPrice($data->getPrice());
        $room->setVacancy($data->isVacancy());

        $roomRepository->save($room);

        return new JsonResponse('', Response::HTTP_CREATED);
    }
}
