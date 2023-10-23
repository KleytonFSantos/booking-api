<?php

namespace App\Command;

use App\Entity\BuyListCards;
use App\Repository\BuyListCardsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use League\Csv\Exception;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use League\Csv\Reader;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'csv:import',
    description: 'Command to import CSV and transform in php/sql lines.',
)]
class CsvToSqlCommand extends Command
{
    const CSV_PATH = '%kernel.root_dir%/../';

    public function __construct(
        private readonly BuyListCardsRepository $buyListCardsRepository,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::OPTIONAL, 'Filename');
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startTime = microtime(true);

        $io = new SymfonyStyle($input, $output);
        $csvFilename = $input->getArgument('file');
        $csvPath = self::CSV_PATH . $csvFilename . '.csv';
        $reader = Reader::createFromPath($csvPath);

        try {
            $results = $reader->getRecords();
        } catch (UnavailableStream $e) {
            $io->error('Failed to open the CSV file.');
            return Command::FAILURE;
        } catch (Exception $e) {
            $io->error('An error occurred while reading the CSV file.');
            return Command::FAILURE;
        }

        $header = null;
        $dataToInsert = [];

        foreach ($results as $row) {
            if (is_null($header)) {
                $header = $row;
            } else {
                if (count($header) === count($row)) {
                    $assocRow = array_combine($header, $row);

                    $listCards = new BuyListCards();
                    $listCards->setName($assocRow['name']);
                    $listCards->setMcm($assocRow['mcm']);
                    $listCards->setValue($assocRow['value']);

                    $dataToInsert[] = $listCards;
                }
            }
        }

        $this->buyListCardsRepository->saveAll($dataToInsert);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        $executionTimeMs = round($executionTime * 1000, 2);

        if ($csvFilename) {
            $io->note(sprintf('You passed the CSV file: %s', $csvFilename));
        }

        $io->success('CSV import complete.');
        $io->success('Tempo de execução: ' . $executionTimeMs . ' ms');

        return Command::SUCCESS;
    }
}
