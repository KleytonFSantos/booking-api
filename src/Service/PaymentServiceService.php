<?php

namespace App\Service;

use App\Entity\Payments;
use App\Entity\User;
use App\Repository\PaymentsRepository;
use Stripe\PaymentIntent;

class PaymentServiceService implements PaymentServiceInterface
{

    public function __construct(
      private readonly PaymentsRepository $paymentsRepository
    ) {

    }
    public function save(Payments $payments): void
    {
        $this->paymentsRepository->save($payments);
    }

    public function paymentsBuilder(User $user, PaymentIntent $paymentIntent): Payments
    {
        $payments = new Payments();

        $payments->setUsers($user);
        $payments->setReservation($user->getReservations()->first());
        $payments->setPaymentMethod($paymentIntent->payment_method);
        $payments->setStatus($paymentIntent->status);
        $payments->setAmount($paymentIntent->amount);
        $payments->setTransactionId($paymentIntent->id);

        return $payments;
    }
}