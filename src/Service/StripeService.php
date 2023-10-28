<?php

namespace App\Service;

use App\DTO\ChargeDTO;
use App\Entity\Payments;
use App\Repository\UserRepository;
use Stripe\Collection;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\Security\Core\User\UserInterface;

class StripeService
{
    const BASE_CURRENCY = 'brl';
    const BASE_TOKEN = 'tok_visa';

    public function __construct(
        private readonly string                  $stripeApiKey,
        private readonly PaymentServiceInterface $paymentService,
        private readonly UserRepository          $userRepository
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
    public function createPaymentIntent(ChargeDTO $chargeDTO, UserInterface $user): void
    {
        $userReserving = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);

        $options = [
            'amount' => $chargeDTO->getAmount(),
            'currency' => self::BASE_CURRENCY,
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ];

         $paymentIntent = $this->getStripeClient()->paymentIntents->create($options);

         $payments = $this->paymentService->builder($userReserving, $paymentIntent);
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