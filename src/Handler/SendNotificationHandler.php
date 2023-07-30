<?php

namespace App\Handler;

use App\Message\SendNotificationMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class SendNotificationHandler
{
    public function __construct(private readonly MessageBusInterface $bus, private readonly MailerInterface $mailer)
    {
    }

    public function __invoke(SendNotificationMessage $message)
    {
        $email = (new TemplatedEmail())
            ->from('teste@email.com')
            ->to('akatsukipb12@gmail.com')
            ->subject('teste')
            ->text($message->getText());

        $this->mailer->send($email);
    }
}
