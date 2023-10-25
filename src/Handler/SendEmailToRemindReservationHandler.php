<?php

namespace App\Handler;

use App\Message\SendEmailToRemindReservationMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SendEmailToRemindReservationHandler
{
    public function __construct(private readonly MessageBusInterface $bus, private readonly MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(SendEmailToRemindReservationMessage $message): void
    {
        $email = (new TemplatedEmail())
            ->from('teste@email.com')
            ->to($message->getEmail())
            ->subject('teste')
            ->htmlTemplate('Emails/send-mail-to-remind-reservations.html.twig')
            ->context([
                'username' => $message->getUsername()
            ]);

        $this->mailer->send($email);
    }
}