<?php

namespace App\Singleton;

class GoogleClientSingleton
{
    private static ?\Google_Client $instance = null;

    private function __construct()
    {
    }


    public static function getInstance(string $credentialsPath): \Google_Client
    {
        if (self::$instance === null) {
            $client = new \Google_Client();
            $client->setApplicationName('XML to Sheets');
            $client->addScope(\Google\Service\Sheets::SPREADSHEETS);
            $client->setAuthConfig($credentialsPath);

            self::$instance = $client;
        }

        return self::$instance;
    }
}
