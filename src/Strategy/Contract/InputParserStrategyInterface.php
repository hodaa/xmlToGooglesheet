<?php

namespace App\Strategy\Contract;

use Generator;

interface InputParserStrategyInterface
{
    public function parse(string $fileSource, array $options = []): array|Generator;
}
