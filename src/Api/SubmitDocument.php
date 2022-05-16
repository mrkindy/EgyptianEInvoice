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

class SubmitDocument extends AbstractApi
{
    /**
     * Submit one or more signed documents to eInvoicing solution using this Method.
     * 
     * @param string $document
     */
    public function submit(array $document)
    {
        $this->makeRequest('POST','/documentsubmissions', 'json',[
                "documents" => $document
        ]);
        return $this->response;
    }
}