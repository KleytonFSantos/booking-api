<?php

namespace App\Infrastructure\Service;

use App\Domain\DTO\ChargeDTO;
use App\Domain\Entity\Payments;
use App\Domain\Entity\Reservation;
use App\Domain\Interface\PaymentServiceInterface;
use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\SearchResult;
use Stripe\StripeClient;

readonly class StripeService
{
    final public const BASE_CURRENCY = 'brl';
    final public const BASE_TOKEN = 'tok_visa';

    public function __construct(
        private string $stripeApiKey,
        private PaymentServiceInterface $paymentService,
    ) {
    }

    /**
     * @throws ApiErrorException
     */
    public function getPaymentIntents(): Collection
    {
        return $this->getStripeClient()->paymentIntents->all();
    }

    /**
     * @throws ApiErrorException
     */
    public function getPaymentIntentByReservationId(Reservation $reservation): SearchResult
    {
        return $this->getStripeClient()->paymentIntents->search([
            'query' => "metadata['reservation_id']:'".$reservation->getId()."'",
        ]);
    }

    /**
     * @throws ApiErrorException
     */
    public function createPaymentIntent(ChargeDTO $chargeDTO, Reservation $reservation): void
    {
        $options = [
            'amount' => $chargeDTO->getAmount(),
            'currency' => self::BASE_CURRENCY,
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ];

        $paymentIntent = $this->getStripeClient()->paymentIntents->create($options);

        $payments = $this->paymentService->builder($paymentIntent, $reservation);
        $this->paymentService->save($payments);
    }

    /**
     * @throws ApiErrorException
     */
    public function updatePaymentIntent(Payments $payments): void
    {
        $options = [
            'amount' => $payments->getAmount(),
            'metadata' => ['reservation_id' => (string) $payments->getReservation()->getId()],
        ];

        $this->getStripeClient()->paymentIntents->update($payments->getTransactionId(), $options);
        $this->paymentService->save($payments);
    }

    /**
     * @throws ApiErrorException
     */
    public function cancelPaymentIntent(Payments $payments): void
    {
        $this->getStripeClient()->paymentIntents->cancel(
            $payments->getTransactionId(),
            []
        );
        $this->paymentService->cancel($payments);
    }

    private function getStripeClient(): StripeClient
    {
        return new StripeClient($this->stripeApiKey);
    }
}