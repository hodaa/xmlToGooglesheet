<?php

namespace App\Reader\Xml;

use Generator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class XmlReaderAbstract
{
    protected int $chunkSize;
    protected string $targetNode;

    public function __construct(private readonly ParameterBagInterface $params)
    {
        $this->chunkSize = $this->params->get('chunk_size');

    }

    abstract protected function readItems(string $file, string $targetNode, bool $readHeader = true): Generator |array ;

    abstract protected function chunkData(array|Generator $rows): Generator;

    /**
    * Read and parse the XML file, returning data in chunks.
    * @param string $xmlSource Path to the XML file.

    */
    public function readXMLFile(string $xmlSource, string $targetNode, bool $readHeader = true): array|Generator
    {
        $items = $this->readItems($xmlSource, $targetNode, $readHeader);
        return $this->chunkData($items);
    }
}
