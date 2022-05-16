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

final class FormateData
{
    /**
     * FormateData to float.
     * @param float $number
     * @return float
     */
    public static function toFloat(float $number)
    {
        return (float)number_format($number,5,'.','');
    }
}