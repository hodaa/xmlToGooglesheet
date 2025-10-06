<?php

namespace App\Service;

use App\Contract\OutputAdapter;
use App\Singleton\GoogleClientSingleton;
use Google\Client;
use Google\Service\Sheets;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GoogleSheetsService implements OutputAdapter
{
    private const VALUE_INPUT_OPTION = 'RAW';
    private const INSERT_DATA_OPTION = 'INSERT_ROWS';
    private const DEFAULT_SHEET_NAME = 'Products!A1';
    private const BATCH_SIZE = 1000;
    private Client $client;

    public function __construct(private LoggerInterface $logger, private ParameterBagInterface $params)
    {
        $this->client = GoogleClientSingleton::getInstance($this->params->get('google_credentials_path'));
    }

    /**
      * Push data to Google Sheets
      *
      * @param array $rows
      * @return bool
      */
    public function push(array $data): bool
    {
        $service = new Sheets($this->client);
        $spreadsheetId = $this->params->get('google_sheet_id');
        $sheetName = self::DEFAULT_SHEET_NAME;

        $params = [
            'valueInputOption' => self::VALUE_INPUT_OPTION,
            'insertDataOption' => self::INSERT_DATA_OPTION,
        ];

        $chunks = array_chunk($data, self::BATCH_SIZE);
        foreach ($chunks as $chunk) {
            $body = new Sheets\ValueRange(['values' => $chunk]);
            try {
                $response =  $service->spreadsheets_values->append($spreadsheetId, $sheetName, $body, $params);
                $this->logger->info(sprintf(
                    'Pushed %d rows to Google Sheets sheet %s',
                    count($chunk),
                    $sheetName
                ));
            } catch (\Exception $e) {
                $this->logger->error(sprintf(
                    'Failed to push chunk of %d rows to Google Sheets: %s',
                    count($chunk),
                    $e->getMessage()
                ));

                return false;
            }
        }
        return true;
    }
}
