<?php

namespace App\Infrastructure\Controller\BookingReview;

use App\Domain\Entity\Reservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewByReservationGetAction extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/booking-review/{reservation}', name: 'app_booking_review', methods: 'GET')]
    public function showReviewByRevervation(Reservation $reservation): JsonResponse
    {
        $booking = $this->serializer->serialize($reservation->getBookingReview(), 'json', ['groups' => 'booking_list']);

        return new JsonResponse($booking, Response::HTTP_OK, [], true);
    }
}
