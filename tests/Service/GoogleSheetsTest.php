<?php

namespace App\Tests\Service;

use App\Service\GoogleSheetsService ;

class GoogleSheetsConnectorTest extends \PHPUnit\Framework\TestCase
{
    public function testConnectConfiguresClientAndReturnsSheets()
    {
        $mockClient = $this->createMock(\Google\Client::class);
        $mockClient->expects($this->once())
            ->method('setAuthConfig')
            ->with('/fake/path/credentials.json');

        $mockClient->expects($this->once())
            ->method('addScope')
            ->with(\Google\Service\Sheets::SPREADSHEETS);

        $connector = new GoogleSheetsService($mockClient, '/fake/path/credentials.json');

        $service = $connector->connect();

        $this->assertInstanceOf(\Google\Service\Sheets::class, $service);
    }
}
