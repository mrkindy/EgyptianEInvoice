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

class CodeReuse extends AbstractApi
{
    /**
     * Request a code reuse of a code that already exist in the solution.
     * 
     * @param string $code
     */
    public function update(array $code)
    {
        $this->makeRequest('PUT','/codetypes/requests/codeusages', 'json',[
                "items" => $code
        ]);
        return $this->response;    
    }
}