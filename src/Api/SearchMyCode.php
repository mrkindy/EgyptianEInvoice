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

class SearchMyCode extends AbstractApi
{
    /**
     * Search EGS code usage retrieving list of requests that were submitted to the solution by the taxpayer.
     * 
     * @param string $code
     */
    public function search(array $parm)
    {
        $this->makeRequest('GET','/codetypes/requests/my', 'form',$parm);
        return $this->response;    
    }
}