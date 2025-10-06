<?php

namespace App\Factory;

use App\Contract\InputParserStrategy;
use App\Strategy\XmlParserStrategy;
use App\Strategy\CsvParserStrategy;

class InputParserFactory
{
    public function __construct(
        private XmlParserStrategy $xmlStrategy,
        private CsvParserStrategy $csvStrategy
    ) {
    }

    public function getStrategy(string $type): InputParserStrategy
    {
        return match(strtolower($type)) {
            'xml' => $this->xmlStrategy,
            'csv' => $this->csvStrategy,
            default => throw new \InvalidArgumentException("Unknown parser type $type"),
        };
    }
}
