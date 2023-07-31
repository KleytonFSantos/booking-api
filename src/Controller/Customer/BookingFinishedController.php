<?php

namespace App\Controller\Customer;

use App\Entity\Reservation;
use App\Exception\ReservationNotFound;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingFinishedController extends AbstractController
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {
    }

    /**
     * @throws ReservationNotFound
     */
    #[Route('/finished/{booking}', name: 'booking_finished', methods: 'GET')]
    public function __invoke(int $booking, UserInterface $user): JsonResponse
    {

        $this->bookingService->finished($booking);

        return new JsonResponse(
            [
                'message' => 'Booking finished successfully',
            ],
            Response::HTTP_OK
        );
    }
}
