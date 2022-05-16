<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kindy\EgyaptianEInvoice\Api;

use Kindy\EgyaptianEInvoice\ETAInvoice;
use Kindy\EgyaptianEInvoice\Util\JsonArray;
use GuzzleHttp\Client as HTTPClient;
use \GuzzleHttp\Exception\ClientException;

abstract class AbstractApi
{

    /**
     * The URI prefix.
     *
     * @var string
     */
    protected $uriPrefix = '/api/v1';

    /**
     * The SIT URL.
     *
     * @var string
     */
    //protected $testURL = 'https://invoicing.entdev.net';
    protected $testURL = 'http://eei.tst';

    /**
     * The SIT URL.
     *
     * @var string
     */
    protected $sitURL = 'https://api.sit.invoicing.eta.gov.eg';
    
    /**
     * The UAT URL.
     *
     * @var string
     */
    protected $uatURL = 'https://api.preprod.invoicing.eta.gov.eg';

    /**
     * The PROD URL.
     *
     * @var string
     */
    protected $prodURL = 'https://api.invoicing.eta.gov.eg';

    /**
     * The ETAInvoice instance.
     *
     * @var ETAInvoice
     */
    protected $invoice;

    /**
     * The per page parameter.
     *
     * @var int|null
     */
    protected $perPage;

    /**
     * HTTP response body.
     *
     * @var object
     */
    public $response;

    /**
     * HTTP response code.
     *
     * @var int
     */
    public $responseCode;

    /**
     * Create a new API instance.
     *
     * @param ETAInvoice $invoice
     *
     * @return void
     */
    public function __construct(ETAInvoice $invoice)
    {
        $this->eta = $invoice;
    }

    /**
     * @param string               $type
     * @param string               $uri
     * @param array<string,mixed>  $params
     * @param array<string,string> $headers
     *
     * @return mixed
     */
    protected function makeRequest(string $type, string $uri, string $paramsType = 'json', array $params = [], array $headers = [])
    {
        $HTTPClient = new HTTPClient();

        $options = $this->prepareHTTPOptions($paramsType, $params, $headers);
        
        try {
            $response = $HTTPClient->request( $type, $this->prepareUri($uri) , $options);
            $this->responseCode = $response->getStatusCode();
            $this->response = JsonArray::decode($response->getBody()->getContents());
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
            $this->responseCode = $response->getStatusCode();
            $this->response = JsonArray::decode($response->getBody()->getContents());
        }
    
    }

    /**
     * Prepare the request URI.
     *
     * @param string $uri
     * @param array  $query
     *
     * @return string
     */
    protected function prepareUri(string $uri)
    {
        return sprintf('%s%s%s', $this->getApiUrl(), $this->uriPrefix, $uri);
    }

    /**
     * Get the Environment URL.
     *
     * @return string
     */
    protected function getApiUrl()
    {
        switch($this->eta->env)
        {
            case 'prod' : return $this->prodURL;
            case 'sit'  : return $this->sitURL;
            case 'uat'  : return $this->uatURL;
            case 'test' : return $this->testURL;
            default     : return $this->prodURL;
        }
    }

    /**
     * Prepare the request JSON body.
     *
     * @param array<string,mixed> $params
     *
     * @return string|null
     */
    protected function prepareJsonBody(array $params)
    {
        $params = array_filter($params, function ($value): bool {
            return null !== $value;
        });

        if (0 === count($params)) {
            return null;
        }

        return JsonArray::encode($params);
    }

    /**
     * Prepare the request Options.
     *
     * @param string $paramsType
     * @param array<string,mixed> $params
     * @param array<string,mixed> $headers
     *
     * @return array
     */
    protected function prepareHTTPOptions(string $paramsType, array $params = [], array $headers = [])
    {
        
        $options = [];
        
        if(isset($this->eta->token))
        {
            $headers['Authorization'] = 'Bearer '.$this->eta->token;
        }

        if(is_array($params) && $paramsType == 'json' )
        {
            $options['json'] = $params;
            $headers['Content-Type'] = 'application/json';
        }
        
        if(is_array($params) && $paramsType == 'form' )
        {
            $options['form_params'] = $params;
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }
        
        $headers['Accept-Language'] = $this->eta->defualt_lang;
        $headers['Accept'] = 'application/json';

        $options['headers'] = $headers;
        return $options;
    }
}