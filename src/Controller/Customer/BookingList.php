<?php

namespace App\Controller\Customer;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BookingList extends AbstractController
{

    #[Route('/booking-list', name: 'booking_list', methods: 'GET')]
    public function __invoke(
        ReservationRepository $reservationRepository,
        SerializerInterface $serializer,

    ): JsonResponse
     {
         $bookingList = $reservationRepository->findAll();
         $allBooking = $serializer->serialize($bookingList, 'json', ['groups' => 'booking_list']);

        return new JsonResponse($allBooking, Response::HTTP_OK, [], true);
     }
}