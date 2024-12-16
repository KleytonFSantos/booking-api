<?php

namespace App\Handler;

use App\Entity\Reservation;
use App\Message\SendEmailToRemindReservationMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailToRemindReservationHandler
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function send(SendEmailToRemindReservationMessage $message): void
    {
        $email = (new TemplatedEmail())
            ->from('teste@email.com')
            ->to($message->getEmail())
            ->subject('teste')
            ->htmlTemplate('Emails/send-mail-to-remind-reservations.html.twig')
            ->context([
                'username' => $message->getUsername(),
            ]);

        $this->mailer->send($email);
    }

    /**
     * @param Reservation[] $reservationsToRemind
     *
     * @throws TransportExceptionInterface
     */
    public function sendAllEmails(array $reservationsToRemind): array
    {
        $messagesArray = [];

        foreach ($reservationsToRemind as $reservation) {
            $user = $reservation->getUser();
            $sendEmailMessage = new SendEmailToRemindReservationMessage(
                email: $user->getEmail(),
                username: $user->getName()
            );

            $this->send($sendEmailMessage);
            $messagesArray[] = "Email was sent to {$user->getEmail()}";
        }

        return $messagesArray;
    }
}
