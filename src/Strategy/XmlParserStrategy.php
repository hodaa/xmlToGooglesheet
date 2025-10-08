<?php

namespace App\Strategy;

use App\Contract\InputParserStrategy;
use App\Exception\NotValidXmlSourceException;
use App\Reader\Xml\XmlReaderAbstract;
use App\Validator\XmlValidator;
use Generator;

class XmlParserStrategy implements InputParserStrategy
{
    public function __construct(private readonly XmlValidator $xmlValidator, private readonly XmlReaderAbstract $xmlReaderAbstract)
    {
        libxml_use_internal_errors(true);
    }

    public function parse(string $xmlSource, bool $readHeader = true): Generator| array
    {
        if (!$this->xmlValidator->isValidSource($xmlSource)) {
            throw new NotValidXMLSourceException($xmlSource);
        }

        return $this->xmlReaderAbstract->readXmlFile($xmlSource, $readHeader);

    }

}
