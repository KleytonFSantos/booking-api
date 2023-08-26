<?php

namespace App\Controller\Customer;

use App\Entity\Room;
use App\Exception\ReservationNotFound;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookingByRoomController extends AbstractController
{
    /**
     * @throws ReservationNotFound
     */
    #[Route('/booking/{room}', name: 'booking_by_room', methods: 'GET')]
    public function __invoke(
        Room $room,
        SerializerInterface $serializer,
        ReservationRepository $reservationRepository,
    ): JsonResponse {
        $bookingByRoom = $reservationRepository->findBy(['room' => $room]);
        $booking = $serializer->serialize($bookingByRoom, 'json', ['groups' => 'booking_list']);

        if (!$bookingByRoom) {
            throw new ReservationNotFound('No reservation found for room '.$room->getId());
        }

        return new JsonResponse($booking, Response::HTTP_OK, [], true);
    }
}
