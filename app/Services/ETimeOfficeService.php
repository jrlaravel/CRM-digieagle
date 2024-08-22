<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class eTimeOfficeService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.etimeoffice.com/', // Replace with the actual base URI of the eTimeOffice API
        ]);
    }

    public function getInOutPunchData($empCode, $fromDate, $toDate)
    {
        try {
            $response = $this->client->request('GET', 'api/DownloadInOutPunchData', [
                'query' => [
                    'Empcode' => $empCode,
                    'FromDate' => $fromDate,
                    'ToDate' => $toDate,
                ],
                'headers' => [
                    'Authorization' => 'Basic REVWQU5HVFJJVkVESTpERVZBTkc6YWRtaW5AMTIzOnRydWU6', // Adjust if needed
                    // Add or adjust other headers if necessary
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
            
        } catch (RequestException $e) {
            // Log detailed error information
            \Log::error('API Request failed: ' . $e->getMessage(), [
                'status_code' => $e->getResponse()->getStatusCode(),
                'response_body' => $e->getResponse()->getBody()->getContents(),
            ]);
            return null;
        } catch (\Exception $e) {
            \Log::error('Unexpected error: ' . $e->getMessage());
            return null;
        }
    }
}
