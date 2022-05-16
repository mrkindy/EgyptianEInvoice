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
 * Class DocumentPayment
 *
 * ETAInvoice document payment
 *
 * @package Kindy\EgyaptianEInvoice
 */
class DocumentPayment
{
    
    public $bankName;
    public $bankAddress;
    public $bankAccountNo;
    public $bankAccountIBAN;
    public $swiftCode;
    public $terms;
    

    /**
     * @param array $payment
     * @param string|null $type
     */
    public function __construct(array $payment)
    {
        if(count($payment)==0)
        {
            return;
        }
        $fields = [
            'bankName',
            'bankAddress',
            'bankAccountNo',
            'bankAccountIBAN',
            'bankAccountIBAN',
            'swiftCode',
            'terms',
        ];
        
        foreach($payment as $key => $val)
        {
            if(in_array($key,$fields))
            {
                $this->$key = $val;
            }
        }
    }
}