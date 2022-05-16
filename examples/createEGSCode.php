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
        "parentCode" => "10007595",
        "itemCode" => "EG-500100200-002",
        "codeName" => "ERP System",
        "codeNameAr" => "نظام إدارة الموارد",
        "activeFrom" => "2022-05-16T00:00:00.000",
        "description" => "ERP System",
        "descriptionAr" => "نظام إدارة الموارد"
];
$createEGSCode = $invoice->createEGSCode($codes);
            
var_dump($createEGSCode);