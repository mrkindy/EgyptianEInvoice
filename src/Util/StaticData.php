<?php

/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kindy\EgyaptianEInvoice\Util;


final class StaticData
{
    public $data;
    protected $availableFiles = ['ActivityCodes','NonTaxableTaxTypes','TaxSubtypes','TaxTypes','UnitTypes'];

    public function __construct(string $fileName = '')
    {
        if(isset($fileName))
        {
            $this->load($fileName);
        }
        
    }
    
    /**
     * Load a JSON string into a PHP array.
     * @param string $fileName
     * @return object
     */
    public function load(string $fileName)
    {
        if(!in_array($fileName, $this->availableFiles))
        {
            return;
        }
        $filedata = file_get_contents(dirname(__FILE__).'/../Data/'.$fileName.'.json');
        $this->data = json_decode($filedata, True);
        return $this;
    }
    /**
     * Search in data.
     * @param string $column
     * @param string $value
     * @return array|null
     */
    public function search(string $column, string $value)
    {
        return $this->data[$this->getKey($column, $value)];
    }
    /**
     * get item key in data.
     * @param string $column
     * @param string $value
     * @return int
     */
    public function getKey(string $column, string $value)
    {
        return array_search($value, array_column($this->data, $column));
    }
}