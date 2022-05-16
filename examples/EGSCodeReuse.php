<?php
/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once "../vendor/autoload.php";
require_once "./config.php";

$invoice = new \Kindy\EgyaptianEInvoice\ETAInvoice($config['client_id'],$config['client_secret'], 'uat');
$codes = [];
$codes[] = [
        "codeType" => "EGS",
        "itemCode" => "EG-500100200-002",
        "comment" => "إعادة استخدام كود 500100200-002"
];
$createEGSCode = $invoice->CodeReuse($codes);
            
var_dump($createEGSCode);