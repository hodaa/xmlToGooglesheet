<?php

namespace App\Service;

use App\Contract\OutputAdapter;
use App\Exception\InvalidConfigurationException;
use App\Factory\InputParserFactory;
use App\Service\Contract\FeedProcessorInterface;
use App\Validator\ConfigurationValidator;
use Psr\Log\LoggerInterface;

class FeedProcessorXml implements FeedProcessorInterface
{
    private const INPUT_FILE_TYPE = 'XML';
    public function __construct(
        private readonly InputParserFactory $parserFactory,
        private readonly OutputAdapter $googleSheetsService,
        private readonly LoggerInterface $logger,
        private readonly ConfigurationValidator $configValidator
    ) {
    }

    public function process(string $fileSource, array $options): bool
    {
        // Validate options before processing
        try {
            $this->configValidator->validateParsingOptions($options);
        } catch (InvalidConfigurationException $e) {
            $this->logger->error('Configuration validation failed: ' . $e->getMessage(), [
                'options' => $options,
                'source' => $fileSource
            ]);
            throw $e;
        }

        $includeHeader = $options['includeHeader'] ?? true;
        try {
            $parser = $this->parserFactory->getStrategy(self::INPUT_FILE_TYPE);
            $rows = $parser->parse($fileSource, $options);
            foreach ($rows as $chunk) {
                $success = $this->googleSheetsService->push($chunk);
            }
            return $success ?? false;

        } catch (\Exception $e) {
            $this->logger->error(sprintf('Failed to process %s feed: %s', self::INPUT_FILE_TYPE, $e->getMessage()), [
                'exception' => $e,
                'source' => $fileSource,
                'type' => self::INPUT_FILE_TYPE,
            ]);
            throw $e;
        }
    }
}
