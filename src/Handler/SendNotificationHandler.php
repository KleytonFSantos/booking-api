<?php

namespace App\Handler;

use App\Message\SendNotificationMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class SendNotificationHandler
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendNotificationMessage $message): void
    {
        $email = (new TemplatedEmail())
            ->from('teste@email.com')
            ->to('akatsukipb12@gmail.com')
            ->subject('teste')
            ->text($message->getText());

        $this->mailer->send($email);
    }
}
