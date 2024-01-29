<?php

namespace App\Service;

use App\Entity\Payments;
use App\Entity\Reservation;
use Stripe\PaymentIntent;

interface PaymentServiceInterface
{
    public function save(Payments $payments): void;

    public function cancel(Payments $payments): void;

    public function builder(PaymentIntent $paymentIntent, Reservation $reservation): Payments;
}
