<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * This class retrieve list of document types published by the eInvoicing and it's versions
 */

namespace Kindy\EgyaptianEInvoice\Api;

class DocumentType extends AbstractApi
{
    /**
     * Retrieve a list of document types published by the eInvoicing.
     */
    public function getTypes()
    {
        $this->makeRequest('GET','/documenttypes');
        return $this->response->result;
    }

    /**
     * Get more details about a single document type by returning list of
     * its versions and also active and historical workflow parameters that
     * define limits for document submission, cancellation and rejection.
     * 
     * @param int $documentTypeID
     */
    public function getTypeByID(int $documentTypeID)
    {
        $this->makeRequest('GET','/documenttypes/'.$documentTypeID);
        return $this->response;
    }

    /**
     * Get details of the document type version that contains also definition of
     * the XML or JSON structures that are expected to be submitted as part of
     * document submission
     * 
     * @param int $documentTypeID
     * @param int $documentTypeVersionID
     */
    public function getTypesVersions(int $documentTypeID, int $documentTypeVersionID)
    {
        $this->makeRequest('GET','/documenttypes/'.$documentTypeID.'/versions/'.$documentTypeVersionID);
        return $this->response;
    }
}