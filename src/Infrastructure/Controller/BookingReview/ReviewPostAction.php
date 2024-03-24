<?php

namespace App\Infrastructure\Controller\BookingReview;

use App\Domain\DTO\BookingReviewRequestDTO;
use App\Domain\Entity\Reservation;
use App\Infrastructure\Service\BookingReviewService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewPostAction extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BookingReviewService $bookingReviewService
    ) {
    }

    #[Route('/booking-review/{reservation}', name: 'app_booking_review_create', methods: 'POST')]
    public function __invoke(
        Request $request,
        Reservation $reservation,
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
}
