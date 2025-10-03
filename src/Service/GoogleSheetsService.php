<?php

namespace App\Service;

use Google\Service\Sheets;
use Google\Client;
use Psr\Log\LoggerInterface;
use App\Singleton\GoogleClientSingleton;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleSheetsService
{
    public function __construct(private LoggerInterface $logger, private Client $client, private ParameterBagInterface $params)
    {
        $this->client = GoogleClientSingleton::getInstance($this->params->get('google_credentials_path'));
        $this->logger = $logger;
    }

    /**
     * @param array $rows
     */
    public function pushToGoogleSheets(array $rows): bool
    {
        $service = new Sheets($this->client);
        $spreadsheetId = $this->params->get('google_sheet_id');
        $sheetName = 'Sheet1';

        $body = new Sheets\ValueRange(['values' => $rows]);
        $params = [
            'valueInputOption' => 'RAW',
            'insertDataOption' => 'INSERT_ROWS',
        ];

        try {
            $service->spreadsheets_values->append($spreadsheetId, $sheetName, $body, $params);
            $this->logger->info('Data pushed to Google Sheets successfully to sheet: ' . $sheetName);

            return true;
        } catch (\Exception $e) {
            $this->logger->error('failed to push: ' . $$e->getMessage());
            return false;
        }
    }
}
