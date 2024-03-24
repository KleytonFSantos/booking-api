<?php

namespace App\Infrastructure\Service;

use App\Domain\DTO\BookingReviewRequestDTO;
use App\Domain\Entity\BookingReview;
use App\Domain\Entity\Reservation;
use App\Domain\Repository\BookingReviewRepository;
use App\Domain\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class BookingReviewService
{
    public function __construct(
        private BookingReviewRepository $bookingReviewRepository,
        private UserRepository $userRepository
    ) {
    }

    public function create(
        BookingReviewRequestDTO $bookingReviewDTO,
        UserInterface $user,
        Reservation $reservation
    ): void {
        $userReserved = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $bookingReview = new BookingReview();
        $bookingReview->setUsers($userReserved);
        $bookingReview->setReservation($reservation);
        $bookingReview->setRating($bookingReviewDTO->getRating()->value);
        $bookingReview->setReview($bookingReviewDTO->getReview());

        $this->bookingReviewRepository->save($bookingReview);
    }

    public function update(
        BookingReviewRequestDTO $bookingReviewDTO,
        BookingReview $review
    ): void {
        $review->setRating($bookingReviewDTO->getRating()->value);
        $review->setReview($bookingReviewDTO->getReview());

        $this->bookingReviewRepository->save($review);
    }

    public function delete(
        BookingReview $review
    ): void {
        $this->bookingReviewRepository->delete($review);
    }
}
