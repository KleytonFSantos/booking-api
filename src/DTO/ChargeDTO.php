<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ChargeDTO
{
    #[Assert\Length(
        min: 50,
    )]
    private int $amount;

    private ?string $description = null;

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

}