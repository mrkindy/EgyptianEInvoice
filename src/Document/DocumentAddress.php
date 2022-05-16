<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Kindy\EgyaptianEInvoice\Document;


/**
 * Class Document
 *
 * ETAInvoice document entity address object
 *
 * @package Kindy\EgyaptianEInvoice
 */
class DocumentAddress
{
    public $country    = "EG";
    public $governate  = "NA";
    public $regionCity = "NA";
    public $street     = "NA";
    

    /**
     * @param array $address
     * @param string|null $type
     */
    public function __construct(array $address, $type='issuer')
    {
        $fields = [
            'country',
            'governate',
            'regionCity',
            'street',
            'buildingNumber',
            'postalCode',
            'floor',
            'room',
            'landmark',
            'additionalInformation',
            'branchID'
        ];
        
        foreach($address as $key => $val)
        {
            if(in_array($key,$fields))
            {
                $this->$key = $val;
            }
        }
    }
}