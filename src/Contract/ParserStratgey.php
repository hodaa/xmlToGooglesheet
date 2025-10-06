<?php

namespace App\Contract;

interface InputParserStratgey
{
    public function parse(string $source): array;
}