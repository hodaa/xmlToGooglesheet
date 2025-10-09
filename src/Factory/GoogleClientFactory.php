<?php

namespace App\Factory;

use Google\Client;
use Google\Service\Sheets;

/**
 * Factory for creating Google Client instances
 * Replaces the problematic Singleton pattern
 */
class GoogleClientFactory
{
    public function __construct(private readonly string $credentialsPath)
    {
    }

    public function create(): Client
    {
        $client = new Client();
        $client->setApplicationName('XML to Sheets');
        $client->addScope(Sheets::SPREADSHEETS);
        $client->setAuthConfig($this->credentialsPath);
        
        return $client;
    }
}