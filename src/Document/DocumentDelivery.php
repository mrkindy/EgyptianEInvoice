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
 * Class DocumentDelivery
 *
 * ETAInvoice document delivery
 *
 * @package Kindy\EgyaptianEInvoice
 */
class DocumentDelivery
{
    public $approach;
    public $packaging;
    public $dateValidity;
    public $exportPort;
    public $countryOfOrigin;
    public $grossWeight;
    public $netWeight;
    public $terms;
    

    /**
     * @param array $delivery
     * @param string|null $type
     */
    public function __construct(array $delivery)
    {
        if(count($delivery)==0)
        {
            return;
        }

        $fields = [
            'approach',
            'packaging',
            'dateValidity',
            'exportPort',
            'countryOfOrigin',
            'grossWeight',
            'netWeight',
            'terms',
        ];
        
        foreach($delivery as $key => $val)
        {
            if(in_array($key,$fields))
            {
                $this->$key = $val;
            }
        }
    }
}