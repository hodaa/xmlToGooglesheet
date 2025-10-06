<?php

namespace App\Contract;

interface InputParserStrategy
{
    public function parse(string $source): array;
}
