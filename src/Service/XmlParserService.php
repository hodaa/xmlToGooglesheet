<?php

namespace App\Service;

use App\Exception\NotValidXMLSourceException;
use App\Validator\XmlValidator;
use SimpleXMLElement;
use XMLReader;

class XmlParserService
{
    public function __construct(private XmlValidator $xmlValidator)
    {
        libxml_use_internal_errors(true);
        $this->xmlValidator = $xmlValidator;
    }

    public function readXMLFile($xmlSource, string $targetNode = 'item', bool $readHeader): array
    {
        if (!$this->xmlValidator->isValidSource($xmlSource)) {
            throw new NotValidXMLSourceException("Invalid XML source: $xmlSource");
        }

        $reader = new XMLReader();
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
            throw new NotValidXMLSourceException("Error reading XML source: " . $e->getMessage());
        }


        $reader->close();
        return $rows;
    }

}
