<?php

namespace App\Command;

use App\Handler\SendEmailToRemindReservationHandler;
use App\Message\SendEmailToRemindReservationMessage;
use App\Repository\ReservationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:bookings:remind',
    description: 'Send email to the users than have booking in the last tree days',
)]
class SendEmailToRemindReservationCommand extends Command
{

    public function __construct(
        private readonly ReservationRepository $repository,
        private readonly SendEmailToRemindReservationHandler $sendEmailToRemindReservationHandler
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    /**
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->createProgressBar();

        $reservationsToRemind = $this->repository->findReservationsToRemind();

        if (empty($reservationsToRemind)) {
            $io->success('No reservations founded to remind');
            return Command::SUCCESS;
        }

        $messagesArray = $this->sendEmailToRemindReservationHandler->sendAllEmails($reservationsToRemind);

        $io->listing($messagesArray);
        $io->success('All emails was sent!');

        return Command::SUCCESS;
    }
}
