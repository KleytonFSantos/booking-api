<?php

namespace App\Infrastructure\Controller\BookingReview;

use App\Domain\Entity\BookingReview;
use App\Infrastructure\Service\BookingReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewDeleteAction extends AbstractController
{
    public function __construct(
        private readonly BookingReviewService $bookingReviewService
    ) {
    }

    #[Route('/booking-review/{review}', name: 'app_booking_review_delete', methods: 'DELETE')]
    public function __invoke(BookingReview $review): JsonResponse
    {
        try {
            $this->bookingReviewService->delete($review);

            return new JsonResponse(['message' => 'The review was deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
