<?php

namespace App\Controller\Charge;

use App\DTO\ChargeDTO;
use App\Service\StripeService;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ChargeController extends AbstractController
{
    public function __construct(
        private readonly StripeService $chargeService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/charge', name: 'app_charge', methods: 'POST')]
    public function createCharge(Request $request): JsonResponse
    {
        try {
            $charge = $this->serializer->deserialize($request->getContent(), ChargeDTO::class, 'json');
            $this->chargeService->createCharge($charge);

            return new JsonResponse(null, Response::HTTP_CREATED);
        } catch (ApiErrorException|\Exception $e) {
            return new JsonResponse([
                'error' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/charge', name: 'app_get_charges', methods: 'GET')]
    public function listCharges(): JsonResponse
    {
        try {
        $charges = $this->chargeService->getCharges();

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
