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

use DateTime;
use Kindy\EgyaptianEInvoice\Exceptions\EgyaptianEInvoiceException;
use Kindy\EgyaptianEInvoice\Util\FormateData;
use Kindy\EgyaptianEInvoice\Util\Signature;

/**
 * Class Document
 *
 * ETAInvoice single document
 *
 * @package Kindy\EgyaptianEInvoice
 */
class Document extends DocumentSetter
{
    /**
     * @var object Representing the issuer information.
     */
    public $issuer;
    /**
     * @var object Representing the receiver information.
     */
    public $receiver;
    /**
     * @var string Document type name. Must be I for invoice.
     */
    public $documentType;
    /**
     * @var string Document type version name. Must be 1.0 for this version.
     */
    public $documentTypeVersion;
    /**
     * @var array of invoice lines of the invoice. Needs to have at least one invoice line.	
     */
    public $invoiceLines = [];
    /**
     * @var DateTime The date and time when the document was issued. Date and time cannot be in future.
     * Time to be supplied in UTC timezone. 2015-02-13T13:15:00Z
     */
    public $dateTimeIssued;
    /**
     * @var string Tax activity code of the business issuing the document representing the activity that caused it to be issued.
     * Must be valid activity type code.	
     */
    public $taxpayerActivityCode;
    /**
     * @var string Internal document ID used to link back to the ERP document number. Value defined by the submitter.
     */
    public $internalID;
    /**
     * @var float Total amount of discounts: sum of all Discount amount elements of InvoiceLine items.
     */
    public $totalDiscountAmount = 0.0;
    /**
     * @var float Sum all all InvoiceLine/SalesTotal items.
     */
    public $totalSalesAmount = 0.0;
    /**
     * @var float netAmount = TotalSales – TotalDiscount
     */
    public $netAmount = 0.0;
    /**
     * @var array of Totals per tax type.
     */
    public $taxTotals = [];
    /**
     * @var float Total amount of the invoice calculated as NetAmount + Totals of tax amounts. 5 decimal digits allowed.
     */
    public $totalAmount = 0.0;
    /**
     * @var float Additional discount amount applied at the level of the overall document, not individual lines.
     */
    public $extraDiscountAmount = 0.0;
    /**
     * @var float Total amount of item discounts: sum of all Item Discount amount elements of InvoiceLine items.	
     */
    public $totalItemsDiscountAmount = 0.0;
    /**
     * @var array Structure containing one or two digital signatures. At least signature of the Issuer must be present.
     * Signature of the Service provider is optional.	
     */
    //public $signatures;

    
    /**
     * Set default element
     */
    public function __construct()
    {
        $this->documentType = "I";
        $this->documentTypeVersion = "1.0";
    }

    /**
     * @return array of optional document element
     */
    protected function optionalElement()
    {
        return [
            'purchaseOrderReference',
            'purchaseOrderDescription',
            'salesOrderReference',
            'salesOrderDescription',
            'proformaInvoiceNumber'
        ];
    }
    
    /**
     * @param array $issuer
     * Set issuer data as DocumnetEntity object
     */
    public function setIssuer($issuer)
    {
        $this->issuer = new DocumnetEntity($issuer);
        return $this;
    }

    /**
     * @param array $receiver
     * Set receiver data as DocumnetEntity object
     */
    public function setReceiver($receiver)
    {
        $this->receiver = new DocumnetEntity($receiver,'receiver');
        return $this;
    }

    /**
     * @param array $payment
     * Set payment data as DocumentPayment object
     * optional
     */
    public function setPayment($payment)
    {
        $this->payment = new DocumentPayment($payment);
        return $this;
    }

    /**
     * @param array $delivery
     * Set delivery data as DocumentDelivery object
     * optional
     */
    public function setDelivery($delivery)
    {
        $this->delivery = new DocumentDelivery($delivery);
        return $this;
    }

    /**
     * @param object $invoiceLine
     * Set invoiceLine data as DocumentInvoiceLine object
     */
    public function setInvoiceLine(DocumentInvoiceLine $invoiceLine)
    {
        $this->invoiceLines[] = $invoiceLine;
        return $this;
    }

    /**
     * Calculate all items
     */
    public function calculate()
    {
        foreach($this->invoiceLines as $item)
        {
            foreach($item->taxableItems as $taxItem)
            {
                $taxTypeExists = array_search($taxItem['taxType'], array_column($this->taxTotals, 'taxType'));
                if(is_int($taxTypeExists))
                {
                    $this->taxTotals[$taxTypeExists]['amount'] += FormateData::toFloat($taxItem['amount']); 
                }else{
                    $this->taxTotals[] = [
                        'taxType' => $taxItem['taxType'],
                        'amount' => FormateData::toFloat($taxItem['amount'])
                    ];
                }
            }
            $this->totalDiscountAmount += FormateData::toFloat($item->discount['amount']);
            $this->totalSalesAmount += FormateData::toFloat($item->salesTotal);
            $this->netAmount += FormateData::toFloat($item->netTotal);
            $this->totalAmount += FormateData::toFloat($item->total);
            $this->totalItemsDiscountAmount+= FormateData::toFloat($item->itemsDiscount);
        }
    }

    /**
     * @param string $dateTime
     * Set Issued DateTime
     */
    public function setDateTimeIssued($dateTime)
    {
        if( strtotime($dateTime) < strtotime('Now') )
        {
            $this->dateTimeIssued = $dateTime;
            return $this;
        }else{
            throw new EgyaptianEInvoiceException('Bad DateTimeIssued');
        }
    }

    /**
     * Get Document Hash
     */
    public function getSerialize()
    {
        return Signature::serialize($this->toArray());
    }
    /**
     * Get Document As Array
     */
    public function toArray()
    {
        return json_decode(json_encode($this),true);
    }

    public function setSignatures($signatures)
    {
        if($this->documentTypeVersion=="1.0")
        {
            $this->signatures = $signatures;
        }
    }
}