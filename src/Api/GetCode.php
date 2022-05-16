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

class GetCode extends AbstractApi
{
    /**
     * retrieving the details for the published code.
     * 
     * @param string $codeType
     * @param string $itemCode
     */
    public function details(string $codeType, string $itemCode)
    {
        $this->makeRequest('GET','/codetypes/'.$codeType.'/codes/'.$itemCode);
        return $this->response;    
    }
}