<?php

namespace App\Strategy;

use App\Contract\InputParserStrategy;
use App\Exception\NotValidXMLSourceException;
use SimpleXMLElement;
use XMLReader;
use App\Validator\XmlValidator;
use App\Service\XmlParserService;


class XmlParserStrategy implements InputParserStrategy
{
    public function __construct(private XmlValidator $xmlValidator, private readonly XmlParserService $xmlParserService)
    {
        libxml_use_internal_errors(true);
        $this->xmlValidator = $xmlValidator;
    }

    public function parse($xmlSource, string $targetNode = 'item', bool $readHeader = true): array
    {
        if (!$this->xmlValidator->isValidSource($xmlSource)) {
            throw new NotValidXMLSourceException("Invalid XML source: $xmlSource");
        }

        return $this->xmlParserService->readXmlFile($xmlSource,$targetNode,$readHeader);

    }

}
