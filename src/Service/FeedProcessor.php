<?php

namespace App\Service;

use App\Contract\OutputAdapter;
use App\Factory\InputParserFactory;
use Psr\Log\LoggerInterface;

class FeedProcessor
{
    public function __construct(
        private readonly InputParserFactory $parserFactory,
        private readonly OutputAdapter $googleSheetsService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function process(string $fileSource, string $fileType, bool $readHeader): bool
    {
        try {
            $parser = $this->parserFactory->getStrategy($fileType);
            $rows = $parser->parse($fileSource, $readHeader);
            foreach ($rows as $chunk) {
                $success = $this->googleSheetsService->push($chunk);
            }
            return $success;

        } catch (\Exception $e) {
            $this->logger->error(sprintf('Failed to process %s feed: %s', $fileType, $e->getMessage()), [
                'exception' => $e,
                'source' => $fileSource,
                'type' => $fileType,
            ]);
            throw $e;
        }
    }
}
