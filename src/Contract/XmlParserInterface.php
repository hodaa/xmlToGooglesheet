<?php

namespace App\Contract;

use Generator;

interface XmlParserInterface
{
    public function readXMLFile(string $xmlSource, string $targetNode = 'item', bool $readHeader = true): array| Generator;
}
