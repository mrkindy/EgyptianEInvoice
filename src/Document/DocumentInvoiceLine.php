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

use Kindy\EgyaptianEInvoice\Exceptions\EgyaptianEInvoiceException;
use Kindy\EgyaptianEInvoice\Util\FormateData;
use Kindy\EgyaptianEInvoice\Util\StaticData;

/**
 * Class DocumentInvoiceLine
 *
 * ETAInvoice document InvoiceLine
 *
 * @package Kindy\EgyaptianEInvoice
 */
class DocumentInvoiceLine extends DocumentSetter
{
    /**
     * Description of the item being sold.	
     */
    public $description;
    /**
     * Coding schema used to encode the item type. Must be GS1 or EGS for this version.	
     */
    public $itemType;
    /**
     * Code of the goods or services item being sold.
     * GS1 codes targeted for managing goods, EGS codes targeted for managing goods – goods or services.	
     */
    public $itemCode;
    /**
     * Code of the unit type used from the code table of the measures.
     */
    public $unitType;
    /**
     * Number of units of the defined unit type being sold. Number should be larger than 0.	
     */
    public $quantity = 0;
    /**
     * Optional: Internal code used for the product being sold – can be used to simplify references back to existing solution.
     */
    public $internalCode;
    /**
     * Total amount for the invoice line considering quantity and unit price in EGP
     * (with excluded factory amounts if they are present for specific types in documents).	
     */
    public $salesTotal = 0.0;
    /**
     * Total amount for the invoice line after adding all pricing items, taxes, removing discounts.	
     */
    public $total = 0.0;
    /**
     * Value difference when selling goods already taxed (accepts +/- numbers), e.g., factory value based.	
     */
    public $valueDifference = 0.0;
    /**
     * Total amount of additional taxable fees to be used in final tax calculation.	
     */
    public $totalTaxableFees = 0.0;
    /**
     * Total amount for the invoice line after applying discount.	
     */
    public $netTotal = 0.0;
    /**
     * Non-taxable items discount (Discount After Tax).
     */
    public $itemsDiscount = 0.0;
    /**
     * The structure defining the price of a single unit sold.	
     */
    public $unitValue = [
        "currencySold" => "EGP",
        "amountEGP" => 0.0
    ];
    /**
     * Optional: the structure defining the discount applied on a single unit sold.	
     */
    public $discount = [
        'rate'   => 0,
        'amount' => 0
    ];
    /**
     * Optional: List of taxable items. Can have zero or more of supported tax items below
     * from the list of all tax types including VAT, WHT and table tax, local authority fees (municipality), development.	
     */
    public $taxableItems = [];

    protected $totalTax;
    /**
     * Set Coding schema used to encode the item type. Must be GS1 or EGS for version 1.0.
     * @param string $itemType
     */
    public function setItemType(string $itemType)
    {
        // check if itemType is valid
        if( $itemType == "GS1" || $itemType == "EGS" )
        {
            $this->itemType = $itemType;
        }
        return $this;
    }
    /**
     * Set Unit Type Code of the unit type used from the code table of the measures.
     * @param string $unitType
     */
    public function setUnitType(string $unitType)
    {   
        // check if UnitTypes is valid
        $UnitTypeDocument = new StaticData('UnitTypes');
        if($UnitTypeDocument->getKey('code',$unitType))
        {
            $this->unitType = $unitType;
        }
        return $this;
    }
    /**
     * Set The structure defining the price of a single unit sold.	.
     * @param string $unitType
     */
    public function setUnitValue(string $currencySold, float $amountEGP, float $amountSold = 0.0, float $currencyExchangeRate = 0.0)
    {
        $unitValue = [];
        $unitValue['currencySold'] = $currencySold;
        $unitValue['amountEGP'] = $amountEGP;
        // check if amountSold is greater than 0
        if( $amountSold > 0 )
        {
            $unitValue['amountSold'] = $amountSold;
        }
        // check if currencyExchangeRate is greater than 0
        if( $currencyExchangeRate > 0 )
        {
            $unitValue['currencyExchangeRate'] = $currencyExchangeRate;
        }
        // assign the unitValue
        $this->unitValue = $unitValue;
        return $this;
    }
    /**
     * Set The structure defining the price of a single unit sold.	.
     * @param string $unitType
     */
    public function setDiscount(float $number, string $type='P')
    {
        $this->setSalesTotal();
        $this->discount = [
            'rate'      => $type == 'P' ? $number : FormateData::toFloat(( $number / $this->salesTotal )*100),
            'amount'    => $type == 'A' ? FormateData::toFloat($number) : FormateData::toFloat((($number/100) * $this->salesTotal)),
        ];
        return $this;
    }
    /**
     * Set The structure defining the price of a single unit sold.	.
     * @param string $unitType
     */
    public function setTaxableItems(string $taxType, float $amount, float $rate, string $subType = '')
    {
        // check if taxType is valid
        $taxTypeDocument = new StaticData('TaxTypes');
        if(!is_array($taxTypeDocument->search('Code',$taxType)))
        {
            throw new EgyaptianEInvoiceException('Tax Type Not Found');
        }

        $this->setNetTotal();
        // Build taxableItems array
        $setTaxableItems = [
            'taxType' => $taxType,
            'amount' => $amount,
            'rate' => $rate,
        ];
        // check is this line is sub type
        if($subType!='')
        {
            // check if subType is valid
            $subTaxTypeDocument = new StaticData('TaxSubtypes');
            $subTaxTypeArray = $subTaxTypeDocument->search('Code',$subType);
            if(!is_array($subTaxTypeArray))
            {
                throw new EgyaptianEInvoiceException('SubTax Type Not Found');
            }

            // check if subType is a sub type of current tax
            if($subTaxTypeArray['TaxtypeReference'] != $taxType)
            {
                throw new EgyaptianEInvoiceException($subType.' not a sub type of '.$taxType);
            }
            // set subtyp to the taxableItems array
            $setTaxableItems['subType']=$subType;
        }
        // check if this taxableItems array already exists
        $taxExists = array_filter($this->taxableItems, function($item) use ($taxType,$subType) {
            if($subType)
            {

                return ($item['taxType'] == $taxType && isset($item['subType']) && $item['subType'] == $subType);
            }
            return ($item['taxType']==$taxType);
        });
        if($taxExists)
        {
            throw new EgyaptianEInvoiceException($taxType.' already exists');
        }

        // assign the taxableItems
        $this->taxableItems[] = $setTaxableItems;
        $this->setTotalTax();
        return $this;
    }
    /**
     * Calculate totalTaxableFees.
     */
    public function setTotalTax()
    {
        $totalTax = 0;
        foreach($this->taxableItems as $tax)
        {
            if($tax['taxType']!='T4')
            {
                $totalTax += $tax['amount'];
            }else{
                $totalTax -= $tax['amount'];
            }
        }
        $this->totalTax = FormateData::toFloat($totalTax);
    }
    /**
     * Calculate salesTotal.
     */
    public function setSalesTotal()
    {
        $this->salesTotal = FormateData::toFloat($this->unitValue['amountEGP'] * $this->quantity);
    }
    /**
     * Calculate netTotal.
     */
    public function setNetTotal()
    {
        $this->setSalesTotal();
        $this->netTotal = FormateData::toFloat($this->salesTotal - $this->discount['amount']);
    }
    /**
     * Calculate total.
     */
    public function setTotal()
    {
        $this->setTotalTax();
        $this->setNetTotal();
        $this->total = FormateData::toFloat($this->netTotal + $this->totalTax - $this->itemsDiscount);
    }
    
}