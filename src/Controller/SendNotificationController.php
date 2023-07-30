<?php

namespace App\Controller;

use App\Message\SendNotificationMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class SendNotificationController extends AbstractController
{
    #[Route('/send-notification', name: 'app_send_notification', methods: 'GET')]
    public function __invoke(MessageBusInterface $bus): Response
    {
        $bus->dispatch(new SendNotificationMessage('Hello test'));

        return new Response('', Response::HTTP_OK);
    }
}
