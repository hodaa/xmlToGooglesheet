<?php

namespace App\Strategy;

use App\Contract\InputParserStrategy;

class CsvParserStrategy implements InputParserStrategy
{
    public function parse(string $fileSource, bool $readHeader = true): array
    {
        // Implement CSV parsing logic here
        return [];
    }
}
