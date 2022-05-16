<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * This class is used to authenticate the calling and issue access token that allows
 * to access those protected APIs. Note that each token issued is issued for a certain 
 * time period configured as part of e-invoicing solution
 */

namespace Kindy\EgyaptianEInvoice\Api;

class Login extends AbstractApi
{
    

    /**
     * The URI prefix.
     *
     * @var string
     */
    protected $uriPrefix = '';
    /**
     * The SIT URL.
     *
     * @var string
     */
    protected $sitURL = 'https://id.sit.eta.gov.eg';
    
    /**
     * The UAT URL.
     *
     * @var string
     */
    protected $uatURL = 'https://id.preprod.eta.gov.eg';

    /**
     * The PROD URL.
     *
     * @var string
     */
    protected $prodURL = 'https://id.eta.gov.eg';

    /**
     * Get access token to be used to access other protected APIs of the solution.
     */
    public function getToken()
    {
        $prams = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->eta->client_id,
            'client_secret' => $this->eta->client_secret,
            'scope' => 'InvoicingAPI'
        ];
        $this->makeRequest('POST','/connect/token','form',$prams);
        if($this->responseCode == 200)
        {
            return $this->response->access_token;
        }else{
            return $this->response;
        }
    }
}