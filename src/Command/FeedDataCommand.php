<?php

namespace App\Command;

use App\Service\XmlParserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Contract\OutputAdapter;
use App\Service\CommandPusher;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'xml:feed-data',
    description: 'process a local or remote XML file and push the data of that XML file to a Google Spreadsheet via the Google Sheets API!'
)]

class FeedDataCommand extends Command
{
    private const DEFAULT_TARGET_NODE = 'item';

    public function __construct(
        private readonly XmlParserService $xmlParserService,
        private readonly OutputAdapter $googleSheetsService
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

        $rows = $this->xmlParserService->readXMLFile($xmlSource, $targetNode, $readHeader);
        $success = $this->googleSheetsService->push($rows);
        $output->writeln(
            $success
            ? "<info>Data successfully pushed to Google Sheets.</info>"
            : "<error>Failed to push data to Google Sheets.</error>"
        );

        return $success ? Command::SUCCESS : Command::FAILURE;
    }
}
