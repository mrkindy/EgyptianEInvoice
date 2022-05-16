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

class DocumentChangeStatus extends AbstractApi
{
    /**
     * Cancel or reject previously issued document.
     * 
     * @param string $documentUUID
     */
    public function changeStatus(string $documentUUID,array $data)
    {        
        $this->makeRequest('PUT','/documents/state/'.$documentUUID.'/state', 'json',[
                "status" => $data['status'],
                "reason" => $data['reason']
        ]);
        
        return $this->response;
    }
}