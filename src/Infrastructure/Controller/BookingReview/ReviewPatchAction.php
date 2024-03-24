<?php

namespace App\Infrastructure\Controller\BookingReview;

use App\Domain\DTO\BookingReviewRequestDTO;
use App\Domain\Entity\BookingReview;
use App\Infrastructure\Service\BookingReviewService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ReviewPatchAction extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BookingReviewService $bookingReviewService
    ) {
    }

    #[Route('/booking-review/{review}', name: 'app_booking_review_update', methods: 'PATCH')]
    public function __invoke(
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
}
