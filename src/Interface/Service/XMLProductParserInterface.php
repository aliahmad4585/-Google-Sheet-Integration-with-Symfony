<?php

namespace App\Interface\Service;

interface  XMLProductParserInterface
{
    public function parseXmlObjectToArray($objXmlDocument): array;
}
