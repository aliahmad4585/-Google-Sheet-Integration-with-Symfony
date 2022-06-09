<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Parser\XMLProductParserService;
use App\Service\Sheet\GoogleSheetService;
use App\Service\Log\GoogleSheetCommandLog;
use App\Exception\GoogleSheetCommandException;
use App\Exception\XMLProductParserException;
use App\Exception\GoogleSheetOperationException;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:upload-data')]
class GoogleSheetUploadDataCommand extends Command
{
    /**
     * @var \App\Service\XMLProductParserService
     */
    private XMLProductParserService $productParser;

    /**
     * @var \App\Service\Log\GoogleSheetCommandLog $logger
     */
    private $logger;

    /**
     * @var App\Service\GoogleSheetService $logger
     */
    private $googleSheet;

    /**
     * Inject the parser use Symfony's dependency injection.
     *
     * @param App\Service\XMLProductParserService $productParser
     * @param App\Service\GoogleSheetService $sheet
     * @param App\Service\Log\GoogleSheetCommandLog $logger
     */

    public function __construct(
        XMLProductParserService $productParser,
        GoogleSheetService $sheet,
        GoogleSheetCommandLog $logger,
    ) {
        $this->productParser = $productParser;
        $this->googleSheet =  $sheet;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {

            $this->logger->logExecutionStartMessage("Command Execuation start");
            $objXmlDocument =  $this->productParser->loadProductXml();

            // check if the exist on the given path
            if (!$objXmlDocument) {
                $this->logger->logError("XML file not found");
                return  Command::FAILURE;
            }

            // check if the XML content is in right format
            if (!$this->productParser->checkIfXMLObjectIsValid($objXmlDocument)) {

                $errors =  $this->productParser->parseXmlLibraryErrors();
                $this->logger->logError("There were errors parsing the XML file", $errors);
                return  Command::FAILURE;
            }

            $client =  $this->googleSheet->getClient();

            //check $client is valid object 
            if (!$this->googleSheet->checkIfGoogleClientValid($client)) {

                $this->logger->logError("Google client is not valid instance");
                return  Command::FAILURE;
            }

            $products =  $this->productParser->parseXmlObjectToArray($objXmlDocument);

            //if the products is not empty
            if (!count($products)) {

                $this->logger->logError("No product exist");
                return  Command::FAILURE;
            }

            //write the date on sheet

            echo "Please Wait ......";

            $headings =  $this->googleSheet->setColumnHeadingInSheet($client);
            $response =  $this->googleSheet->writeDataOnSheet($client, $products);

            if (count($response)) {
                echo "Done";
                $this->logger->logInfo("Successfully wrote the data");
                return Command::SUCCESS;
            }
            
            $this->logger->logError("Data while writing the data", [$response]);
            return  Command::FAILURE;
        } catch (XMLProductParserException | GoogleSheetCommandException | GoogleSheetOperationException | \Exception $th) {

            $this->logger->logError("Exception occured", [$th->getMessage()]);
            return Command::FAILURE;
        }
    }

    /**
     * Configures the current command.
     */
    protected function configure(): void
    {
        $this
            ->setName('app:google-sheet-upload-data')
            ->setDescription('The program should process a
            local or remote XML file and push the data of that XML file to a Google Spreadsheet via the
            Google Sheets API');
    }
}
