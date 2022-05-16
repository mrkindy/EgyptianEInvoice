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
 * Class DocumentSetter
 *
 * Set Element class
 *
 * @package Kindy\EgyaptianEInvoice
 */
class DocumentSetter
{

    /**
     * @param array $elements
     * Set Array Of Elements
     */
    public function setArrayOfElements($elements)
    {
        foreach($elements as $key => $val)
        {
            $this->setElement($key,$val);
        }
    }
    
    /**
     * @param string $element
     * @param string|array $val
     * Set One Element
     */
    public function setElement($element,$val)
    {
        $optionalElement = $this->optionalElement();
        
        if(property_exists($this,$element) || in_array($element,$optionalElement))
        {
            $this->$element = $val;
        }
    }

    /**
     * @param string $method
     * @param array $arguments
     * Magic method handel none-existing method  
     */
    public function __call($method, $arguments)
    {
        if(!str_starts_with($method, 'set'))
        {
            return $this;
        }

        $extractedMethod = explode('set',$method);
        $element = lcfirst(end($extractedMethod));
        $val = current($arguments);
        $this->setElement($element,$val);
        return $this;
    }
}