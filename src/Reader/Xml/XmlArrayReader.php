<?php

namespace App\Reader\Xml;

use App\Exception\NotValidXMLSourceException;
use App\Reader\Xml\Exception\XmlFileEmptyException;
use Generator;
use SimpleXMLElement;
use XMLReader;

class XmlArrayReader extends XmlReaderAbstract
{
    /**
     * Read and parse the XML file, returning data in chunks.
     *
     * @param string $xmlSource Path to the XML file.
     * @param string $targetNode The XML node to target for extraction.
     * @param bool $readHeader Whether to read and include headers.
     * @return array Chunks of parsed XML data.
     * @throws NotValidXMLSourceException If the XML source is not valid or cannot be read.
     */
    protected function readItems(string $xmlSource, string $targetNode, bool $readHeader = true): Generator| array
    {
        $reader = new XMLReader($xmlSource);
        try {
            $reader->open($xmlSource);
            $rows = [];
            while ($reader->read()) {
                if ($reader->nodeType == XMLReader::ELEMENT && $reader->name === $targetNode) {

                    $node = new SimpleXMLElement($reader->readOuterXML());

                    $row = [];
                    if ($readHeader) {
                        $headers = array_keys((array)$node);
                        $rows[] = $headers;
                        $readHeader = false;
                    }
                    foreach ($node as $child) {
                        $row[] = (string)$child;
                    }
                    $rows[] = $row;
                }

            }
            if (empty($rows)) {
                throw new XmlFileEmptyException("No $targetNode elements found in XML file: $xmlSource");
            }
        } catch (\Exception $e) {
            throw new NotValidXMLSourceException('Error reading XML source: ' . $e->getMessage());
        }

        $reader->close();
        return $rows;
    }
    /**
     * Summary of chunkData
     * @param array|\Generator $rows
     * @return Generator
     */
    protected function chunkData(array|Generator $rows): Generator
    {
        foreach (array_chunk($rows, $this->chunkSize) as $chunk) {
            yield $chunk;
        }
    }


}
