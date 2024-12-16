<?php

namespace App\Controller\Customer;

use App\Exception\ReservationNotFound;
use App\Service\BookingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingCancelController extends AbstractController
{
    public function __construct(
        private readonly BookingService $bookingService,
    ) {
    }

    /**
     * @throws ReservationNotFound
     */
    #[Route('/cancel/{booking}', name: 'booking_cancel', methods: 'GET')]
    public function __invoke(int $booking, UserInterface $user): JsonResponse
    {
        $this->bookingService->cancel($booking);

        return new JsonResponse(
            [
                'message' => 'Booking '.$booking.' was canceled successfully',
            ],
            Response::HTTP_OK
        );
    }
}
