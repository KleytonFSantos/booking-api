<?php

namespace App\Domain\Interface;

use App\Domain\Entity\Payments;
use App\Domain\Entity\Reservation;
use Stripe\PaymentIntent;

interface PaymentServiceInterface
{
    public function save(Payments $payments): void;

    public function cancel(Payments $payments): void;

    public function builder(PaymentIntent $paymentIntent, Reservation $reservation): Payments;
}
