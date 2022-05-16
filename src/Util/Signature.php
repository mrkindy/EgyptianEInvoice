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

final class Signature
{

    public static function hashedSerializedData($documentStructure)
    {
        $serializedData = self::serialize($documentStructure);
        return hash('sha256', $serializedData);
    }

    public static function serialize($documentStructure)
    {
        if(!is_array($documentStructure))
        {
            //if(gettype($documentStructure)=='double'||gettype($documentStructure)=='integer')
            //{
                //return '"'.number_format($documentStructure,2).'"';    
            //}
            return '"'.$documentStructure.'"';
        }

        $serializedString = "";
        
        foreach($documentStructure as $item => $value)
        {
            
            if(!is_array($value))
            {
                $serializedString .= strtoupper('"'.$item.'"');
                $serializedString .= self::serialize($value);
            }

            if(is_array($value))
            {
                $serializedString .= strtoupper('"'.$item.'"');
                foreach($value as $subItem => $subValue)
                {
                    $serializedString .= is_int($subItem)?strtoupper('"'.$item.'"'):strtoupper('"'.$subItem.'"');      
                    $serializedString .= self::serialize($subValue);             
                }
            }
        }

        return $serializedString;
    }
}
