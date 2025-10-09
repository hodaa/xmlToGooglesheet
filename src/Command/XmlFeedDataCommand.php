<?php

namespace App\Command;

use App\Exception\InvalidConfigurationException;
use App\Exception\XmlParsingException;
use App\Service\Contract\FeedProcessorInterface;
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
    private const TARGET_NODE = 'item';


    public function __construct(
        private readonly FeedProcessorInterface $feedProcessor,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument('xmlSource', InputArgument::REQUIRED, 'Path or URL of XML file')
         ->addArgument('targetNode', InputArgument::REQUIRED, 'XML target node to extract data from')
             ->addOption(
                 'header',
                 null,
                 InputOption::VALUE_NEGATABLE,
                 'Whether to include the header row in the google sheet ',
                 true
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $xmlSource = $input->getArgument('xmlSource');

        $options = [
            'includeHeader' => $input->getOption('header'),
            'targetNode' => $input->getArgument('targetNode') ?? self::TARGET_NODE ,
        ];

        try {
            $success = $this->feedProcessor->process($xmlSource, $options);
            if (!$success) {
                $output->writeln('<error>Failed to push data to Google Sheets.</error>');
                return Command::FAILURE;
            }
            $output->writeln('<info>Data successfully pushed to Google Sheets.</info>');
            return Command::SUCCESS;

        } catch (InvalidConfigurationException $e) {
            $errorMessage = sprintf('Configuration validation failed: %s', $e->getMessage());
            $this->logger->error($errorMessage, [
                'exception' => $e,
                'xmlSource' => $xmlSource,
                'options' => $options
            ]);
            $output->writeln('<error>Configuration Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        } catch (XmlParsingException $e) {
            $errorMessage = sprintf('Failed to process XML feed: %s', $e->getMessage());
            $this->logger->error($errorMessage, [
                'exception' => $e,
                'xmlSource' => $xmlSource
            ]);
            $output->writeln('<error>Error: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
    }
}
