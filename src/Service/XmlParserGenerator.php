<?php

namespace App\Service;

use Generator;
use XMLReader;

class XmlParserGenerator
{
    public function parse(string $file): Generator
    {
        $reader = new XMLReader();
        $reader->open($file);

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT && $reader->name === 'item') {
                yield simplexml_load_string($reader->readOuterXML());
            }
        }

        $reader->close();
    }
}
