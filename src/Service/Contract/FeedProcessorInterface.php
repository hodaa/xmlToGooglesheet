<?php

namespace App\Service\Contract;

interface FeedProcessorInterface
{
    public function process(string $fileSource, array $options): bool;
}
