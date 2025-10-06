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

    public function process(string $source, string $type, string $node, bool $header): bool
    {
        try {
            $parser = $this->parserFactory->getStrategy($type);
            $rows = $parser->parse($source, $node, $header);
            return $this->googleSheetsService->push($rows);
        } catch (\Exception $e) {
            $this->logger->error(sprintf('Failed to process %s feed: %s', $type, $e->getMessage()), [
                'exception' => $e,
                'source' => $source,
                'type' => $type,
            ]);
            throw $e;
        }
    }
}
