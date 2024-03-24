<?php

namespace App\Infrastructure\Controller\Payment;

use App\Domain\DTO\ChargeDTO;
use App\Domain\DTO\UpdatePaymentRequestDTO;
use App\Domain\Entity\Reservation;
use App\Domain\Exception\PaymentNotFound;
use App\Infrastructure\Service\StripeService;
use Exception;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PaymentController extends AbstractController
{
    public function __construct(
        private readonly StripeService $chargeService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/{reservation}/payment', name: 'app_payment', methods: 'POST')]
    public function createPayment(Request $request, Reservation $reservation): JsonResponse
    {
        try {
            $charge = $this->serializer->deserialize($request->getContent(), ChargeDTO::class, 'json');
            $this->chargeService->createPaymentIntent($charge, $reservation);

            return new JsonResponse(null, Response::HTTP_CREATED);
        } catch (ApiErrorException|Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/cancel-payment/{booking}', name: 'app_cancel_payment', methods: 'PATCH')]
    public function cancelPayment(Reservation $booking): JsonResponse
    {
        try {
            $payment = $booking->getPayments();
            $this->chargeService->cancelPaymentIntent($payment);

            return new JsonResponse([
                'message' => 'Payment canceled successfully.',
            ], Response::HTTP_OK);
        } catch (ApiErrorException|Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/update-payment/{booking}', name: 'app_update_payment', methods: 'PATCH')]
    public function updatePayment(Request $request, Reservation $booking): JsonResponse
    {
        try {
            $paymentRequest = $this->serializer->deserialize($request->getContent(), UpdatePaymentRequestDTO::class, 'json');
            $payment = $booking->getPayments();

            if (!$payment) {
                throw new PaymentNotFound('No payment founded to this reservation');
            }

            $payment->setAmount($paymentRequest->getAmount());
            $this->chargeService->updatePaymentIntent($payment);

            return new JsonResponse([
                'message' => 'Payment updated successfully.',
            ], Response::HTTP_OK);
        } catch (PaymentNotFound $exception) {
            return new JsonResponse([
                'error' => $exception->getMessage(),
            ], $exception->getCode());
        } catch (ApiErrorException|Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/payment', name: 'app_get_payments', methods: 'GET')]
    public function listPayments(): JsonResponse
    {
        try {
            $charges = $this->chargeService->getPaymentIntents();

            return new JsonResponse([
                'charges' => $charges,
            ]);
        } catch (ApiErrorException|Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/payment/{reservation}', name: 'app_get_payments_by_reservation', methods: 'GET')]
    public function getPaymentByReservationId(Reservation $reservation): JsonResponse
    {
        try {
            $charges = $this->chargeService->getPaymentIntentByReservationId($reservation);

            return new JsonResponse([
                'charges' => $charges,
            ]);
        } catch (ApiErrorException|Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
