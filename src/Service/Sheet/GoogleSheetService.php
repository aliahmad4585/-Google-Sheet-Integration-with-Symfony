<?php

// https://docs.google.com/spreadsheets/d/1nlMpbq-EC_QOHkRA9DyAibjDFKD_YzNvb4hcCTmajKc/edit#gid=0
// https://docs.google.com/spreadsheets/d/1nlMpbq-EC_QOHkRA9DyAibjDFKD_YzNvb4hcCTmajKc/edit?usp=sharing

//docker-compose build
//docker-compose up -d

// docker export --output="upload-data-to-sheet.tar" my_project_directory-web-1

//> php bin/phpunit


namespace App\Service\Sheet;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_BatchUpdateValuesRequest;
use App\Interface\Service\GoogleSheetInterface;
use App\Exception\GoogleSheetOperationException;
use App\Service\Log\GoogleSheetLog;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use App\Factory\GoogleServiceFactory;

class GoogleSheetService implements GoogleSheetInterface
{

    private $envParams;
    private $googleSheetLogger;
    private $googleServiceFactory;
    /**
     * Inject the parser use Symfony's dependency injection.
     *
     * @param \Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface
     */

    public function __construct(
        ContainerBagInterface $envParams,
        GoogleSheetLog $googleSheetLogger,
        GoogleServiceFactory $googleServiceFactory
    ) {
        $this->envParams = $envParams;
        $this->googleSheetLogger = $googleSheetLogger;
        $this->googleServiceFactory =  $googleServiceFactory;
    }

    /**
     * get google client to interact with google services
     * 
     * @return object $client
     */
    public function getClient(): object
    {
        try {

            $credentials =  $this->getCredentials();
            $client = $this->googleServiceFactory->create("Google_Client");
            $client->setApplicationName('Google Sheets');
            $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
            $client->setAuthConfig($credentials);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
        } catch (GoogleSheetOperationException $th) {
            $this->googleSheetLogger->logGoogleClientError(
                "Error while getting the google client",
                [
                    $th->getMessage()
                ]
            );
            throw new GoogleSheetOperationException("Error while getting the google client");
        }

        return $client;
    }

    /**
     * get google services instance
     * 
     * @return object $serviceInstance
     */
    public function getGoogleSheetServiceInstance($client)
    {
        $serviceInstance = new Google_Service_Sheets($client);
        return $serviceInstance;
    }

    /**
     * get sheet values.
     * it return the row as array
     * @param object $googleClient
     * 
     * @return array $sheetValues
     */

    public function getSheetValues($googleClient): array
    {
        $spreadsheetId =  $this->getSpreedSheetId();
        $service =  $this->getGoogleSheetServiceInstance($googleClient);
        $range = $spreadsheetId;
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $sheetValues = $response->getValues();
        return $sheetValues;
    }

    /**
     * write the data on google sheet.
     * it return the row as array
     * 
     * @param object $googleClient
     * @param array $values
     * 
     * @return array $result
     */
    public function writeDataOnSheet($googleClient, $values)
    {
        try {

            $service =  $this->getGoogleSheetServiceInstance($googleClient);
            $spreadsheetId =  $this->getSpreedSheetId();

            $body = new Google_Service_Sheets_BatchUpdateValuesRequest([
                'valueInputOption' => 'USER_ENTERED',
                'data' => $values
            ]);

            // Use Batch processing to update the data
            $result = $service->spreadsheets_values->batchUpdate($spreadsheetId, $body);
            return $result;
        } catch (GoogleSheetOperationException $th) {
            $this->googleSheetLogger->logWriteDateOnSheetError(
                "Error while writing data on google sheet",
                [
                    $th->getMessage()
                ]
            );
            throw new GoogleSheetOperationException("Error while writing data on google sheet");
        }
    }

    /**
     * set the sheet headings.
     * it return the row as array
     * 
     * @param object $googleClient
     * 
     * @return array $result
     */

    public function setColumnHeadingInSheet($googleClient)
    {
        try {
            $service =  $this->getGoogleSheetServiceInstance($googleClient);
            $spreadsheetId =  $this->getSpreedSheetId();
            $sheetName =  $this->getSpreedSheetName();
            $completeSheet = $sheetName . '!A1:R1';

            $options = array('valueInputOption' => 'RAW');
            $values = [
                $this->getColumnHeading()
            ];

            $body   = new Google_Service_Sheets_ValueRange(['values' =>  $values]);
            $result = $service->spreadsheets_values->update($spreadsheetId, $completeSheet, $body, $options);

            return $result->updatedRange;
        } catch (GoogleSheetOperationException $th) {
            $this->googleSheetLogger->logWriteDateOnSheetError(
                "Error while writing data on google sheet",
                [
                    $th->getMessage()
                ]
            );
            throw new GoogleSheetOperationException("Error while writing data on google sheet");
        }
    }


    public function checkIfGoogleClientValid($googleClient)
    {
        if ($googleClient instanceof Google_Client) {
            return true;
        }
        return false;
    }

    /** Get the Googel Sheet ID */
    public function getSpreedSheetId()
    {
        return $this->envParams->get('app.googleSheetId');
    }

    /** Get the Googel Sheet name */
    public function getSpreedSheetName()
    {
        return $this->envParams->get('app.googleSheetName');
    }

    /** Get the Googel Sheet credentials */
    public function getCredentials()
    {
        return [
            "type" => $this->envParams->get('app.googleAccountType'),
            "project_id" => $this->envParams->get('app.googleProjectId'),
            "private_key_id" => $this->envParams->get('app.googlePrivateKeyId'),
            "private_key" => $this->envParams->get('app.googlePrivateKey'),
            "client_email" => $this->envParams->get('app.googleClientEMail'),
            "client_id" => $this->envParams->get('app.googleClientId'),
            "auth_uri" => $this->envParams->get('app.googleAuthUri'),
            "token_uri" => $this->envParams->get('app.googleTokenUri'),
            "auth_provider_x509_cert_url" => $this->envParams->get('app.googleAuthProviderX509CertUrl'),
            "client_x509_cert_url" => $this->envParams->get('app.googleClientX509CertUrl'),
        ];
    }

    /** Get the Googel Sheet column headings */
    public function getColumnHeading()
    {
        return  [
            "ID",
            "Name",
            "Category",
            "Sku",
            "Description",
            "Short Description",
            "Price",
            "Link",
            "Image",
            "Brand",
            "Rating",
            "Caffeine Type",
            "Count",
            "Flavored",
            "Seasonal",
            "In Stock",
            "Facebook",
            "IsKCup"
        ];
    }
}
