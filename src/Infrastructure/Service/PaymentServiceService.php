<?php

namespace App\Infrastructure\Service;

use App\Domain\Entity\Payments;
use App\Domain\Entity\Reservation;
use App\Domain\Interface\PaymentServiceInterface;
use App\Domain\Repository\PaymentsRepository;
use Stripe\PaymentIntent;

readonly class PaymentServiceService implements PaymentServiceInterface
{
    public function __construct(
        private PaymentsRepository $paymentsRepository
    ) {
    }

    public function save(Payments $payments): void
    {
        $this->paymentsRepository->save($payments);
    }

    public function cancel(Payments $payments): void
    {
        $this->paymentsRepository->cancel($payments);
    }

    public function builder(PaymentIntent $paymentIntent, Reservation $reservation): Payments
    {
        $payments = new Payments();

        $payments->setUsers($reservation->getUser());
        $payments->setReservation($reservation);
        $payments->setPaymentMethod($paymentIntent->payment_method);
        $payments->setStatus($paymentIntent->status);
        $payments->setAmount($paymentIntent->amount);
        $payments->setTransactionId($paymentIntent->id);

        return $payments;
    }
}
