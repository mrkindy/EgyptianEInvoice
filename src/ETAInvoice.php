<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kindy\EgyaptianEInvoice;

use Kindy\EgyaptianEInvoice\Api\CodeReuse;
use Kindy\EgyaptianEInvoice\Api\CreateEGSCode;
use Kindy\EgyaptianEInvoice\Api\DocumentChangeStatus;
use Kindy\EgyaptianEInvoice\Api\DocumentType;
use Kindy\EgyaptianEInvoice\Api\GetCode;
use Kindy\EgyaptianEInvoice\Api\GetDocument;
use Kindy\EgyaptianEInvoice\Api\Login;
use Kindy\EgyaptianEInvoice\Api\SearchMyCode;
use Kindy\EgyaptianEInvoice\Api\SubmitDocument;
use Kindy\EgyaptianEInvoice\Exceptions\EgyaptianEInvoiceException;

/**
 * Class ETAInvoice
 *
 * The main class for API consumption
 *
 * @package Kindy\EgyaptianEInvoice
 */
class ETAInvoice
{
    /**
     *  @var string The API Issued Client ID
     */
    public $client_id = null;
    /**
     *  @var string The API Issued Client Secret
     */
    public $client_secret = null;
    /**
     *  @var string The API Issued Client Secret
     */
    public $defualt_lang = 'ar';
    /**
     *  @var string The API access token
     */
    public $token = null;
    /**
     * Singleton 
     */
    private $instances = [];


    /**
     * @param string|null $client_id Issued Client ID
     * @param string|null $client_secret Issued Client Secret
     * @throws EgyaptianEInvoiceException When no token is provided
     */
    public function __construct(string $client_id, string $client_secret, string $env = '')
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->env = $env;
        $this->generateToken();
    }

    /**
     * Sets the token for all future new instances
     * @param string $token string The API access token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * Sets the token for all future new instances
     * @param string $token string The API access token
     */
    public function setDefualtLang(string $lang)
    {
        $this->defualt_lang = $lang;
    }

    /**
     * retrive the token
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Generate the token
     * @param $token string The API access token
     */
    private function generateToken()
    {
        $token = $this->login()->getToken();
        $this->token = $token;
    }

    /**
     * @return Login
     */
    private function login()
    {
        return new Login($this);
    }

    /**
     * @return documentType
     */
    public function documentType()
    {
        if (!isset($this->instances['documentType'])) {
            $this->instances['documentType'] = new DocumentType($this);
        }

        return $this->instances['documentType'];
    }

    /**
     * @param array $document 
     * @return object
     */
    public function submitDocument(array $document)
    {
        $submitDocument = new SubmitDocument($this);
        return $submitDocument->submit($document);
    }

    /**
     * @param string $documentUUID 
     * @param string $type raw|details|pdf default raw
     * @return object
     */
    public function getDocument(string $documentUUID, string $type = 'raw')
    {
        $getDocument = new GetDocument($this);
        return $getDocument->getDocument($documentUUID, $type);
    }

    /**
     * @param string $documentUUID 
     * @param Array $data 
     * @return object
     */
    public function documentChangeStatus(string $documentUUID, array $data)
    {
        $documentChangeStatus = new DocumentChangeStatus($this);
        return $documentChangeStatus->changeStatus($documentUUID, $data);
    }

    /**
     * @param array $code 
     * @return object
     */
    public function createEGSCode(array $code)
    {
        $createEGSCode = new CreateEGSCode($this);
        return $createEGSCode->create($code);
    }

    /**
     * @param int $PageSize
     * @param int $PageNumber
     * @param string $Status (Submitted, Approved, Rejected)
     * @param bool $Active
     * @param string $OrderDirections (Descending, Ascending)
     * @return object
     */
    public function searchMyCode(int $PageSize = 10,int $PageNumber = 1, string $Status = "Approved", bool $Active = true, string $OrderDirections = "Descending")
    {
        $searchMyCode = new SearchMyCode($this);
        $parm = [
            'PageSize'        => $PageSize,
            'PageNumber'      => $PageNumber,
            'Status'          => $Status,
            'Active'          => $Active,
            'OrderDirections' => $OrderDirections
        ];
        return $searchMyCode->search($parm);
    }

    /**
     * @param array $code 
     * @return object
     */
    public function codeReuse(array $code)
    {
        $codeReuse = new CodeReuse($this);
        return $codeReuse->update($code);
    }

    /**
     * @param string $codeType
     * @param string $itemCode
     * @return object
     */
    public function getCode(string $codeType, string $itemCode)
    {
        $getCode = new GetCode($this);
        return $getCode->details($codeType, $itemCode);
    }
}