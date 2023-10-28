<?php

namespace App\Controller\Payment;

use App\DTO\ChargeDTO;
use App\Service\StripeService;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PaymentController extends AbstractController
{
    public function __construct(
        private readonly StripeService $chargeService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/payment', name: 'app_payment', methods: 'POST')]
    public function createPayment(Request $request, UserInterface $user): JsonResponse
    {
        try {
            $charge = $this->serializer->deserialize($request->getContent(), ChargeDTO::class, 'json');
            $this->chargeService->createPaymentIntent($charge, $user);

            return new JsonResponse(null, Response::HTTP_CREATED);
        } catch (ApiErrorException|\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/payment', name: 'app_get_payments', methods: 'GET')]
    public function listPayments(): JsonResponse
    {
        try {
            $charges = $this->chargeService->getPaymentIntents();

            return new JsonResponse([
                'charges' => $charges
            ]);
        } catch (ApiErrorException|\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
