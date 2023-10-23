<?php

namespace App\DTO;

use App\Enum\RatingEnum;
use Symfony\Component\Validator\Constraints as Assert;

class BookingReviewRequestDTO
{
    #[Assert\Type(type: RatingEnum::class, message: 'This rate is not available.')]
    private RatingEnum $rating;

    #[Assert\Length(max: 255)]
    private ?string $review;

    public function getRating(): RatingEnum
    {
        return $this->rating;
    }

    public function setRating(RatingEnum $rating): void
    {
        $this->rating = $rating;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): void
    {
        $this->review = $review;
    }
}