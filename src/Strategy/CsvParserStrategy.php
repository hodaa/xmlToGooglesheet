<?php

namespace App\Strategy;

use App\Strategy\Contract\InputParserStrategyInterface;

class CsvParserStrategy implements InputParserStrategyInterface
{
    public function parse(string $fileSource, array $options = []): array
    {
        // Implement CSV parsing logic here
        return [];
    }
}
