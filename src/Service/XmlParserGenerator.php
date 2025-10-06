<?php

namespace App\Service;

use Generator;
use XMLReader;

class XmlParserGenerator 
{
    private const CHUNK_SIZE = 1000;

    private function readItems(string $file): Generator
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
    /**
     * Chunk the generator into smaller arrays
     *  @param Generator $generator
     *  @return Generator
     */
    private function chunkGenerator(Generator $generator): Generator
    {
        $chunkSize = self::CHUNK_SIZE;
        $chunk = [];
        foreach ($generator as $item) {
            $chunk[] = $item;
            if (count($chunk) >= $chunkSize) {
                yield $chunk;
                $chunk = [];
            }
        }
        if ($chunk) {
            yield $chunk;
        }
    }

    public function readXMLFile(string $xmlSource): array|Generator
    {
        return $this->chunkGenerator($this->readItems($xmlSource));
    }
}
