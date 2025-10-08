<?php

namespace App\Reader\xml;

use Generator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class XmlReaderAbstract
{
    protected int $chunkSize;
    protected string $targetNode;

    public function __construct(private readonly ParameterBagInterface $params)
    {
        $this->chunkSize = $this->params->get('chunk_size');
        $this->targetNode = $this->params->get('xml.target_node');

    }

    abstract protected function readItems(string $file, bool $readHeader = true): Generator |array ;

    abstract protected function chunkData(array|Generator $rows): Generator;

    /**
    * Read and parse the XML file, returning data in chunks.
    * @param string $xmlSource Path to the XML file.

    */
    public function readXMLFile(string $xmlSource, bool $readHeader = true): array|Generator
    {
        return $this->chunkData($this->readItems($xmlSource, $readHeader));
    }
}
