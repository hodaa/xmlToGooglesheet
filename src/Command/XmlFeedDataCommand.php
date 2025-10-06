<?php

namespace App\Command;

use App\Contract\OutputAdapter;
use App\Factory\InputParserFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'xml:feed-data',
    description: 'process a local or remote XML file and push the data of that XML file to a Google Spreadsheet via the Google Sheets API!'
)]


class XmlFeedDataCommand extends Command
{
    private const DEFAULT_TARGET_NODE = 'item';
    private const INPUT_FILE_TYPE = 'XML';


    public function __construct(
        private readonly InputParserFactory $parserFactory,
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
                 'Whether to include the header row',
                 true
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xmlSource = $input->getArgument('xmlSource');
        $targetNode = $input->getArgument('targetNode');
        $readHeader = $input->getOption('header');

        try {

            $parser = $this->parserFactory->getStrategy(self::INPUT_FILE_TYPE);
            $rows = $parser->parse($xmlSource, $targetNode, $readHeader);
            $this->googleSheetsService->push($rows);
            $output->writeln('<info>Data successfully pushed to Google Sheets.</info>');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $errorMessage = sprintf('Failed to process XML feed: %s', $e->getMessage());
            $this->logger->error($errorMessage, [
                'exception' => $e,
                'xmlSource' => $xmlSource,
                'targetNode' => $targetNode,
            ]);
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
