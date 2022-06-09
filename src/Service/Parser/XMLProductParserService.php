<?php

namespace App\Service\Parser;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use App\Interface\Service\XMLProductParserInterface;
use App\Exception\XMLProductParserException;
use App\Service\Log\ProductParserLog;
use App\Entity\Product;

class XMLProductParserService implements XMLProductParserInterface
{

    private $sheetName;
    private $envParams;
    private $productXMLFilePath;
    private $parserLogger;

    /**
     * Inject env variable through ContainerBaig use Symfony's dependency injection.
     *
     * @param Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface $envParams
     */
    public function __construct(ContainerBagInterface $envParams, ProductParserLog $parserLogger)
    {
        $this->envParams = $envParams;
        $this->sheetName =  $this->envParams->get('app.googleSheetName');
        $this->productXMLFilePath = $this->envParams->get('app.productXMLFilePath');
        $this->parserLogger =  $parserLogger;
    }

    /**
     * Load the XML file from path set in ENV file.
     */

    public function loadProductXml()
    {
        try {
            if (file_exists($this->productXMLFilePath)) {
                $objXmlDocument = simplexml_load_file($this->productXMLFilePath, null, LIBXML_NOCDATA);
                return $objXmlDocument;
            }
            return false;
        } catch (XMLProductParserException $th) {
            $this->parserLogger->logExceptionOnXmlLoad(
                "Error while loading the xml file",
                [$th->getMessage()]
            );
            throw new XMLProductParserException("Error while loading the xml file");
        }
    }

    /**
     * Parse the XML Object into mutliple associate array
     * 
     * @param object $objXmlDocument
     * 
     * @return array $products
     */

    public function parseXmlObjectToArray($objXmlDocument): array
    {
        $products = [];

        try {
            // First ROW will be columns headings
            $idx =  2;
            foreach ($objXmlDocument->item as $item) {
                $prd =  $this->convertXMLObjectToArray($item);
                $products[] = $this->prepareColumnValue($prd['entity_id'], "A", $idx);
                $products[] = $this->prepareColumnValue($prd['name'], "B", $idx);
                $products[] = $this->prepareColumnValue($prd['CategoryName'], "C", $idx);
                $products[] = $this->prepareColumnValue($prd['sku'], "D", $idx);
                $products[] = $this->prepareColumnValue($prd['description'], "E", $idx);
                $products[] = $this->prepareColumnValue($prd['shortdesc'], "F", $idx);
                $products[] = $this->prepareColumnValue($prd['price'], "G", $idx);
                $products[] = $this->prepareColumnValue($prd['link'], "H", $idx);
                $products[] = $this->prepareColumnValue($prd['image'], "I", $idx);
                $products[] = $this->prepareColumnValue($prd['Brand'], "J", $idx);
                $products[] = $this->prepareColumnValue($prd['Rating'], "K", $idx);
                $products[] = $this->prepareColumnValue($prd['CaffeineType'], "L", $idx);
                $products[] = $this->prepareColumnValue($prd['Count'], "M", $idx);
                $products[] = $this->prepareColumnValue($prd['Flavored'], "N", $idx);
                $products[] = $this->prepareColumnValue($prd['Seasonal'], "O", $idx);
                $products[] = $this->prepareColumnValue($prd['Instock'], "P", $idx);
                $products[] = $this->prepareColumnValue($prd['Facebook'], "Q", $idx);
                $products[] = $this->prepareColumnValue($prd['IsKCup'], "R", $idx);
                $idx++;
            }
            return $products;
        } catch (XMLProductParserException $th) {
            $this->parserLogger->logExceptionOnProductParse(
                "There were errors parsing the XML file",
                [$th->getMessage()]
            );
            throw new XMLProductParserException("There were errors parsing the XML file");
        }
    }

    /**
     * Convert Single product xml object into array
     * 
     * @param object $xmlObject
     * 
     * @return array $singleProduct
     */
    public function convertXMLObjectToArray($xmlObject)
    {
        $singleProduct =  (array) $xmlObject;
        return $singleProduct;
    }

    /**
     * check that if the product has value
     * if attribute is instance of SimpleXMLElement class then it's consider a empty value
     * 
     * @param string $attribute
     * 
     * @return string $attribute | null
     */
    public function checkIfSimpleXMLObject($attribute)
    {
        if ($attribute instanceof \SimpleXMLElement) {
            return null;
        }
        return $attribute;
    }

    /**
     * convert the product object into associative array
     * 
     * @param array $objectsArray
     * 
     * @return array $mutliArray
     */
    public function serializeObjectsToArray($objectsArray)
    {
        $serializer = new Serializer(array(new ObjectNormalizer()));
        $mutliArray = $serializer->normalize($objectsArray, null);

        return $mutliArray;
    }

    /**
     * check the XML File has valid format.
     * 
     * @param object $objXmlDocument
     * 
     * @return boolean 
     */
    public function checkIfXMLObjectIsValid($objXmlDocument)
    {
        if ($objXmlDocument === False) {
            return false;
        }
        return true;
    }

    /**
     * prepare the google sheet column with range and value
     * 
     * @param string $value
     * @param string $column
     * @param int $idx
     * 
     * @return array $column 
     */
    public function prepareColumnValue($value, $column, $idx)
    {

        $column =
            [
                'range' => "$this->sheetName!$column$idx",
                'values' => array(
                    array($this->checkIfSimpleXMLObject($value))
                )
            ];

        return $column;
    }

    /**
     * if the simpl_xml_parse library unable to load the xml file
     * then libxml_get_errors store all the errors
     * 
     * @return array $errors 
     */
    public function parseXmlLibraryErrors()
    {
        $errors = [];
        foreach (libxml_get_errors() as $error) {
            $errors[] =  $error->message;
        }

        return $errors;
    }
}
