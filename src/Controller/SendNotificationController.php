<?php

namespace App\Controller;

use App\Message\SendNotificationMessage;
use App\Teste\TesteQueue;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SendNotificationController extends AbstractController
{

    #[Route('/send-notification', name: 'app_send_notification', methods: 'GET')]
    public function __invoke(MessageBusInterface $bus, Request $request): Response
    {
        $queryParam = $request->query->get('teste');

        $bus->dispatch(new TesteQueue($queryParam));
        $bus->dispatch(new SendNotificationMessage($queryParam));

        return new Response('success', Response::HTTP_OK);
    }
}
