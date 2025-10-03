<?php

namespace App\Service;

use SimpleXMLElement;
use XMLReader;

class XmlParserService
{
    public function readXMLFile($xmlSource): array
    {
        $reader = new XMLReader();
        $reader->open($xmlSource);
        $firstItem = true;
        $rows = [];
        while ($reader->read()) {
            if ($reader->nodeType == XMLReader::ELEMENT && $reader->name === 'item') {
                $node = new SimpleXMLElement($reader->readOuterXML());

                if ($firstItem) {
                    // Generate headers dynamically from first <item>
                    $headers = array_keys((array)$node);
                    $rows[] = $headers;
                    $firstItem = false;
                }
                // Populate row values in the same order as headers
                $row = [];
                foreach ($headers as $key) {
                    $row[] = (string)($node->$key ?? '');
                }
                $rows[] = $row;
            }

        }
        $reader->close();
        return $rows;
    }

}
