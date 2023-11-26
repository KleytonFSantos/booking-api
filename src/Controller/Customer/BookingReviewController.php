<?php

namespace App\Controller\Customer;

use App\DTO\BookingReviewRequestDTO;
use App\Entity\BookingReview;
use App\Entity\Reservation;
use App\Service\BookingReviewService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
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

    #[Route('/booking-review/{reservation}', name: 'app_booking_review', methods: 'GET')]
    public function showReviewByRevervation(Reservation $reservation): JsonResponse
    {
        $booking = $this->serializer->serialize($reservation->getBookingReview(), 'json', ['groups' => 'booking_list']);
        return new JsonResponse($booking, Response::HTTP_OK, [], true);
    }

    #[Route('/booking-review/{reservation}', name: 'app_booking_review_create', methods: 'POST')]
    public function create(
        Request       $request,
        Reservation   $reservation,
        UserInterface $user
    ): JsonResponse {
        try {
            $data = $this->serializer->deserialize($request->getContent(), BookingReviewRequestDTO::class, 'json');
            $this->bookingReviewService->create($data, $user, $reservation);

            return new JsonResponse(['message' => 'The review was send successfully'], Response::HTTP_CREATED);
        } catch (UniqueConstraintViolationException) {
            return new JsonResponse(['message' => 'The reservation already have a review'], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/booking-review/{review}', name: 'app_booking_review_update', methods: 'PATCH')]
    public function update(
      Request $request,
      BookingReview $review,
    ): JsonResponse {
        try {
            $data = $this->serializer->deserialize(
                $request->getContent(),
                BookingReviewRequestDTO::class,
                'json'
            );

            $this->bookingReviewService->update($data, $review);

            return new JsonResponse(['message' => 'The review was updated successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/booking-review/{review}', name: 'app_booking_review_delete', methods: 'DELETE')]
    public function delete(BookingReview $review): JsonResponse
    {
        try {
            $this->bookingReviewService->delete($review);
            return new JsonResponse(['message' => 'The review was deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
