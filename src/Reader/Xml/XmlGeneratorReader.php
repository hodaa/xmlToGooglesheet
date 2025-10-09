<?php

namespace App\Reader\Xml;

use App\Reader\Xml\Exception\XmlFileEmptyException;
use Generator;
use SimpleXMLElement;
use XMLReader;

class XmlGeneratorReader extends XmlReaderAbstract
{
    /**
     * Read items from an XML file and yield them as arrays.
     * @param string $file Path to the XML file.
     * @param bool $readHeader Whether to read and include headers.
     * @return Generator Yields arrays of XML data.
     */
    protected function readItems(string $xmlSource, string $targetNode, bool $readHeader = true): Generator |array
    {
        $reader = new XMLReader();
        $reader->open($xmlSource);

        while ($reader->read()) {
            if ($reader->nodeType === XMLReader::ELEMENT  && $reader->name === $targetNode) {
                $node =  new SimpleXMLElement($reader->readOuterXML());
                if ($readHeader) {
                    $headers = array_keys((array)$node);
                    yield $headers;
                    $readHeader = false;
                }

                $row = [];
                foreach ($node as $child) {
                    $row[] = (string)$child;
                }

                yield $row;
            }
        }
        $reader->close();
    }

    /**
     * Chunk the generator into smaller arrays
     *  @param Generator $generator
     *  @return Generator <int, array<string>>
     */
    protected function chunkData(array|Generator $generator): Generator
    {
        $firstItem = $generator->valid() ? $generator->current() : null;
        if ($firstItem === null) {
            throw new XmlFileEmptyException();
        }

        $chunk = [];
        foreach ($generator as $item) {
            $chunk[] = $item;
            if (count($chunk) === $this->chunkSize) {
                yield $chunk;
                $chunk = [];
                unset($chunk);
            }

        }
        if ($chunk) {
            yield $chunk;
        }
    }
}
