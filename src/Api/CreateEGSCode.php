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

class CreateEGSCode extends AbstractApi
{
    /**
     * Create EGS Code Usage API is a way for taxpayer to register his own internal codes in the eInvicing solution.
     * 
     * @param string $code
     */
    public function create(array $code)
    {
        $this->makeRequest('POST','/codetypes/requests/codes', 'json',[
                "items" => $code
        ]);
        return $this->response;    
    }
}