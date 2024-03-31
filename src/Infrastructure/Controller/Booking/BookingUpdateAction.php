<?php

namespace App\Infrastructure\Controller\Booking;

use App\Domain\DTO\ReservationUpdateDTO;
use App\Infrastructure\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookingUpdateAction extends AbstractController
{

    /**
     * @throws \Exception
     */
    #[Route('/booking/{bookingId}', name: 'app_booking_update', methods: ['PUT', 'PATCH'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        BookingService $bookingService,
        int $bookingId
    ): JsonResponse {
        $data = $serializer->deserialize($request->getContent(), ReservationUpdateDTO::class, 'json');
        $updatedReservation = $bookingService->updateBooking($data, $bookingId);

        return new JsonResponse(
            $serializer->serialize($updatedReservation, 'json', ['groups' => 'booking_list']),
            Response::HTTP_OK,
            [],
            true
        );
    }
}
