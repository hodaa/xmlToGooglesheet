<?php

namespace App\Factory;

use App\Strategy\Contract\InputParserStrategyInterface;
use App\Strategy\CsvParserStrategy;
use App\Strategy\XmlParserStrategy;

class InputParserFactory
{
    public function __construct(
        private readonly XmlParserStrategy $xmlStrategy,
        private readonly CsvParserStrategy $csvStrategy,
    ) {
    }

    public function getStrategy(string $type): InputParserStrategyInterface
    {
        return match(strtolower($type)) {
            'xml' => $this->xmlStrategy,
            'csv' => $this->csvStrategy,
            default => throw new \InvalidArgumentException("Unknown parser type $type"),
        };
    }
}
