<?php

namespace App\Service;

use App\DTO\ChargeDTO;
use Stripe\BaseStripeClientInterface;
use Stripe\Charge;
use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Stripe\StripeClientInterface;

class StripeService
{
    const BASE_CURRENCY = 'brl';
    const BASE_TOKEN = 'tok_visa';

    public function __construct(private readonly string $stripeApiKey)
    {
    }

    /**
     * @throws ApiErrorException
     */
    public function getCharges(): Collection
    {
        return $this->getStripeClient()->charges->all();
    }

    /**
     * @throws ApiErrorException
     */
    public function createCharge(ChargeDTO $chargeDTO): void
    {
        $options = [
            'amount' => $chargeDTO->getAmount(),
            'currency' => self::BASE_CURRENCY,
            'source' => self::BASE_TOKEN,
            'description' => $chargeDTO->getDescription()
        ];

         $this->getStripeClient()->charges->create($options);
    }

    private function getStripeClient(): StripeClient
    {
        return new StripeClient($this->stripeApiKey);
    }
}