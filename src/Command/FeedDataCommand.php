<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Contract\OutputAdapter;
use App\Contract\InputParserStratgey;
use Symfony\Component\Console\Input\InputOption;
use Psr\Log\LoggerInterface;

#[AsCommand(
    name: 'xml:feed-data',
    description: 'process a local or remote XML file and push the data of that XML file to a Google Spreadsheet via the Google Sheets API!'
)]


class FeedDataCommand extends Command
{
    private const DEFAULT_TARGET_NODE = 'item';

    public function __construct(
        private readonly InputParserStratgey $xmlParser,
        private readonly OutputAdapter $googleSheetsService,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('xmlSource', InputArgument::REQUIRED, 'Path or URL of XML file')
             ->addArgument('targetNode', InputArgument::OPTIONAL, 'Target XML node to parse', self::DEFAULT_TARGET_NODE)
             ->addOption(
                 'header',
                 null,
                 InputOption::VALUE_NEGATABLE,
                 'Whether you want to include the header row',
                 true
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xmlSource = $input->getArgument('xmlSource');
        $targetNode = $input->getArgument('targetNode');
        $readHeader = $input->getOption('header');

        try {
            $rows = $this->xmlParser->parse($xmlSource, $targetNode, $readHeader);
            $success = $this->googleSheetsService->push($rows);
            $output->writeln("<info>Data successfully pushed to Google Sheets.</info>");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->logger->error("Error: {$e->getMessage()}");
            $output->writeln("<error>Error: " . $e->getMessage() . "</error>");
            return Command::FAILURE;

            return $success ?: Command::FAILURE;
        }
    }
}
