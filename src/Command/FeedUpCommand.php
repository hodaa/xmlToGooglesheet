<?php

namespace App\Command;

use App\Service\XmlParserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\GoogleSheetsService;

#[AsCommand(
    name: 'feed-up',
    description: 'process a local or remote XML file and push the data of that XML file to a Google Spreadsheet via the Google Sheets API!'
)]

class FeedUpCommand extends Command
{
    private XmlParserService $xmlParserService;
    private GoogleSheetsService $googleSheetsService;

    public function __construct(XmlParserService $xmlParserService, GoogleSheetsService $googleSheetsService)
    {
        parent::__construct();
        $this->xmlParserService = $xmlParserService;
        $this->googleSheetsService = $googleSheetsService;
    }

    protected function configure()
    {
        $this->addArgument('xmlSource', InputArgument::REQUIRED, 'Path or URL of XML file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xmlSource = $input->getArgument('xmlSource');
        if (!$xmlSource) {
            $output->writeln("<error>XML source argument is required.</error>");
            log('XML source argument is missing.');
            return Command::FAILURE;
        }

        // $service = $this->googleSheetsService->connect();
        $rows = $this->xmlParserService->readXMLFile($xmlSource);

        if (empty($rows)) {
            $output->writeln("<error>No data found in XML.</error>");
            return Command::FAILURE;
        }
        if ($this->googleSheetsService->pushToGoogleSheets($rows)) {
            $output->writeln("<info>Data successfully pushed to Google Sheets.</info>");
            return Command::SUCCESS;
        } else {
            $output->writeln("<error>Failed to push data to Google Sheets.</error>");
            return Command::FAILURE;
        }
    }
}
