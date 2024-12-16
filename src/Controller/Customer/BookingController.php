<?php

namespace App\Controller\Customer;

use App\DTO\ReservationDTO;
use App\Exception\RoomAlreadyBooked;
use App\Service\BookingService;
use App\Validation\ValidationResponseErrors;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class BookingController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly BookingService $bookingService,
        private readonly ValidationResponseErrors $validationResponse,
    ) {
    }

    /**
     * @throws RoomAlreadyBooked
     */
    #[Route('/booking', name: 'app_booking', methods: 'POST')]
    public function __invoke(Request $request, UserInterface $user): JsonResponse
    {
        $data = $this->serializer->deserialize($request->getContent(), ReservationDTO::class, 'json');

        $this->validationResponse->validate($data);

        $this->bookingService->createBooking($user, $data);

        return new JsonResponse(
            [
                'message' => 'Booking complete successfully',
            ],
            Response::HTTP_OK
        );
    }
}
