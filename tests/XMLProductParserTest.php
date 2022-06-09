<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Service\Parser\XMLProductParserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class XMLProductParserTest extends KernelTestCase
{

    public function testProductXmlLodingSuccessfully(): void
    {
        $xmlProductParser =  XMLProductParserTest::getXMLProductClient();
        $objXmlDocument =  $xmlProductParser->loadProductXml();
        $response = $xmlProductParser->checkIfXMLObjectIsValid($objXmlDocument);
        $this->assertTrue($response);
    }

    public function testProductXmlParsingSuccessfully(): void
    {
        $xmlProductParser =  XMLProductParserTest::getXMLProductClient();
        $objXmlDocument =  $xmlProductParser->loadProductXml();
        $response = $xmlProductParser->parseXmlObjectToArray($objXmlDocument);
        $this->assertGreaterThanOrEqual(1, count($response));
    }

    // check that product xml has not any issue
    public function testProductXmlClassHasAnyError(): void
    {
        $xmlProductParser =  XMLProductParserTest::getXMLProductClient();
        $response = $xmlProductParser->parseXmlLibraryErrors();
        $this->assertEquals(0, count($response));
    }


    private static function getXMLProductClient()
    {
        self::bootKernel();
        // use static::getContainer() to access the service container
        $container = static::getContainer();

        //run some service & test the result
        $XMLProductParserServiceClient = $container->get(XMLProductParserService::class);

        return $XMLProductParserServiceClient;
    }
}
