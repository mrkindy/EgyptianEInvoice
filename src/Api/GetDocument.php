<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * Document Submission it is used to submit one or more documents of different
 * types to the solution. Documents submitted are grouped into the submission
 */

namespace Kindy\EgyaptianEInvoice\Api;

use Kindy\EgyaptianEInvoice\Util\JsonArray;
use GuzzleHttp\Client as HTTPClient;
use \GuzzleHttp\Exception\ClientException;

class GetDocument extends AbstractApi
{
    /**
     * Cancel previously issued document.
     * 
     * @param string $documentUUID
     * @param string $type
     */
    public function getDocument(string $documentUUID, string $type)
    {
        $this->type = $type;
        $this->makeRequest('GET','/documents/'.$documentUUID.'/'.$type);
        return $this->response;
    }
    /**
     * @param string $type
     * @param string $uri
     * @param array $params
     * @param array $headers
     *
     * @return mixed
     */
    protected function makeRequest(string $type, string $uri, string $paramsType = 'form', array $params = [], array $headers = [])
    {
        $HTTPClient = new HTTPClient();

        $options = $this->prepareHTTPOptions($paramsType, $params, $headers);
        
        try {
            $response = $HTTPClient->request( $type, $this->prepareUri($uri) , $options);
            if($this->type == 'pdf')
            {
                $this->response = $response->getBody()->getContents();
                $this->responseCode = $response->getStatusCode();
            }else{
                $this->responseCode = $response->getStatusCode();
                $this->response = JsonArray::decode($response->getBody()->getContents());
            }
        }
        catch (ClientException $e) {
            $response = $e->getResponse();
            $this->responseCode = $response->getStatusCode();
            $this->response = JsonArray::decode($response->getBody()->getContents());
        }
    
    }
}