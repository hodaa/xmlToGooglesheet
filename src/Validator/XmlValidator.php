<?php

namespace App\Validator;

use App\Enum\SourceType;
use App\Exception\NotFoundException;
use App\Exception\NotValidXmlSourceException;
use App\Exception\NotValidXmlUrlException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use XMLReader;

class XmlValidator
{
    public SourceType $sourceType;

    public function __construct(private readonly ParameterBagInterface $params)
    {
        $this->sourceType = SourceType::from($this->params->get('source_type'));

    }

    public function validate($node): bool
    {
        $isValid = true;
        if ($node == XMLReader::ELEMENT) {
            $isValid = true;
        }
        $errors = libxml_get_errors();
        if (!empty($errors)) {
            $isValid = false;
        }
        return $isValid;
    }

    public function isValidSource($xmlSource): bool
    {
        switch ($this->sourceType) {
            case SourceType::LOCAL:
                return $this->isValidLocalFile($xmlSource);
            case SourceType::REMOTE:
                return $this->isValidUrl($xmlSource);
            default:
                throw new NotValidXmlSourceException('Invalid XML file source type: ' . $this->sourceType);
        }
        return false;
    }

    private function isValidUrl($xmlSource): bool
    {
        if (!filter_var($xmlSource, FILTER_VALIDATE_URL)) {
            throw new NotValidXmlUrlException($xmlSource);
        }
        return true;
    }

    private function isValidLocalFile($xmlSource): bool
    {
        if (!file_exists($xmlSource)) {
            throw new NotFoundException($xmlSource);
        }
        return true;
    }
}
