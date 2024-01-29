<?php

namespace App\Service;

use App\DTO\BookingReviewRequestDTO;
use App\Entity\BookingReview;
use App\Entity\Reservation;
use App\Repository\BookingReviewRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class BookingReviewService
{
    public function __construct(
        private readonly BookingReviewRepository $bookingReviewRepository,
        private readonly UserRepository $userRepository
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
