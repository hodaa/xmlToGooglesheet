<?php

namespace App\Validator;

use XMLReader;

class XmlValidator
{
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
        return filter_var($xmlSource, FILTER_VALIDATE_URL) || file_exists($xmlSource);
    }
}
