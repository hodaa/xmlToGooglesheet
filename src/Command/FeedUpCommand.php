<?php

namespace App\Command;

use App\Service\XmlParserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Service\GoogleSheetsService;
use App\Contract\OutputAdapter;
use App\Service\CommandPusher;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'xml:feed-data',
    description: 'process a local or remote XML file and push the data of that XML file to a Google Spreadsheet via the Google Sheets API!'
)]

class FeedUpCommand extends Command
{
    private XmlParserService $xmlParserService;
    private GoogleSheetsService $googleSheetsService;

    public function __construct(XmlParserService $xmlParserService, OutputAdapter $googleSheetsService, private CommandPusher $commandPusher)
    {
        parent::__construct();
        $this->xmlParserService = $xmlParserService;
        $this->googleSheetsService = $googleSheetsService;
    }

    protected function configure()
    {
        $this->addArgument('xmlSource', InputArgument::REQUIRED, 'Path or URL of XML file')
             ->addArgument('targetNode', InputArgument::OPTIONAL, 'Target XML node to parse', 'item')
             ->addOption(
                 'header',
                 null,
                 InputOption::VALUE_OPTIONAL,
                 'Whether you want to include the header row',
                 true
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xmlSource = $input->getArgument('xmlSource');
        $targetNode = $input->getArgument('targetNode');
        $readHeader = filter_var($input->getOption('header'), FILTER_VALIDATE_BOOLEAN);

        if (!$xmlSource) {
            $output->writeln("<error> XML source argument is required.</error>");
            return Command::FAILURE;
        }
        // try {
        //     $this->commandPusher->execute($xmlSource);
        //     $output->writeln("<info>Data successfully pushed to Google Sheets.</info>");
        //     return Command::SUCCESS;
        // } catch (\Exception $e) {
        //     $output->writeln("<error>Error: " . $e->getMessage() . "</error>");
        //     return Command::FAILURE;
        // }

        //TODO: make target node dynamic via argument
        // $targetNode = 'item';
        // $readHeader = true;




        $rows = $this->xmlParserService->readXMLFile($xmlSource, $targetNode, $readHeader);

        if ($this->googleSheetsService->push($rows)) {
            $output->writeln("<info>Data successfully pushed to Google Sheets.</info>");
            return Command::SUCCESS;
        } else {
            $output->writeln("<error>Failed to push data to Google Sheets.</error>");
            return Command::FAILURE;
        }
    }
}
