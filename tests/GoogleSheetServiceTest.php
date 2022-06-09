<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\Sheet\GoogleSheetService;
use Google_Client;

class GoogleSheetServiceTest extends KernelTestCase
{

    public function testGoogleClient()
    {
        $googleSheetServiceClient =  GoogleSheetServiceTest::getGoogleSheetServiceClient();
        $client =  $googleSheetServiceClient->getClient();
        $this->assertInstanceOf(Google_Client::class, $client);
    }

    public function testColumnHeadinsSet()
    {
        $googleSheetServiceClient =  GoogleSheetServiceTest::getGoogleSheetServiceClient();
        $client =  $googleSheetServiceClient->getClient();
        $response =  $googleSheetServiceClient->setColumnHeadingInSheet($client);
        $this->assertStringStartsWith($googleSheetServiceClient->getSpreedSheetName(), $response);
    }

    private static function getGoogleSheetServiceClient(): GoogleSheetService
    {
        self::bootKernel();
        $container = static::getContainer();
        $googleSheetServiceClient = $container->get(GoogleSheetService::class);
        return $googleSheetServiceClient;
    }
}
