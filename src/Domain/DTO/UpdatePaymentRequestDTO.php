<?php

namespace App\Domain\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdatePaymentRequestDTO
{
    #[Assert\Length(
        min: 50,
    )]
    private int $amount;

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }
}
