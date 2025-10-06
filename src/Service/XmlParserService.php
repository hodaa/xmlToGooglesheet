<?php

namespace App\Service;

use App\Exception\NotValidXMLSourceException;
use SimpleXMLElement;
use XMLReader;

class XmlParserService
{
    public function readXmlFile($xmlSource, string $targetNode = 'item', bool $readHeader = true): array
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
                throw new NotValidXMLSourceException("No $targetNode elements found in XML file: $xmlSource");
            }
        } catch (\Exception $e) {
            throw new NotValidXMLSourceException('Error reading XML source: ' . $e->getMessage());
        }

        $reader->close();
        return $rows;
    }
}
