<?php

namespace App\Contract;

use Generator;

interface XmlParser
{
    public function readXMLFile(string  $xmlSource): array|Generator;
}
