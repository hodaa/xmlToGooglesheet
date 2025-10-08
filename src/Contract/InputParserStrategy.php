<?php

namespace App\Contract;

use Generator;

interface InputParserStrategy
{
    public function parse(string $fileType, bool $readHeader = true): array|Generator;
}
