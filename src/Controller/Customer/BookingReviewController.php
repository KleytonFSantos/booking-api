<?php

namespace App\Controller\Customer;

use App\DTO\BookingReviewRequestDTO;
use App\Entity\Reservation;
use App\Service\BookingReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class BookingReviewController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BookingReviewService $bookingReviewService
    ){
    }

    #[Route('/booking-review/{reservation}', name: 'app_booking_review', methods: 'POST')]
    public function create(
        Request $request,
        Reservation $reservation,
        UserInterface $user
    ): JsonResponse {
        $data = $this->serializer->deserialize($request->getContent(), BookingReviewRequestDTO::class, 'json');
        $this->bookingReviewService->create($data, $user, $reservation);

        return new JsonResponse(['message' => 'The review was send successfully'],Response::HTTP_CREATED);
    }
}
