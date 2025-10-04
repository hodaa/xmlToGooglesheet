<?php

namespace App\Service;

use Iterator;
use XMLReader;
use SimpleXMLElement;

class XmlStreamIterator implements Iterator
{
    private XMLReader $xml;
    private ?SimpleXMLElement $currentNode = null;
    private int $key = 0;
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->xml = new XMLReader();
        $this->xml->open($file);
        $this->next();
    }

    public function rewind(): void
    {
        $this->xml->close();
        $this->xml->open($this->file);
        $this->key = 0;
        $this->next();
    }

    public function current(): array
    {
        if (!$this->currentNode) {
            return [];
        }

        $values = [];
        foreach ($this->currentNode as $child) {
            $values[] = (string)$child;
        }

        return $values;
    }

    public function key(): int
    {
        return $this->key;
    }

    public function next(): void
    {
        $this->currentNode = null;
        while ($this->xml->read()) {
            if ($this->xml->nodeType === XMLReader::ELEMENT && $this->xml->name === 'item') {
                $this->currentNode = simplexml_load_string($this->xml->readOuterXML());
                $this->key++;
                break;
            }
        }
    }

    public function valid(): bool
    {
        return $this->currentNode !== null;
    }
}
