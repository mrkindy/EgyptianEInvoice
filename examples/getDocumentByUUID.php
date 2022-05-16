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

$documentUUID = 'AXJE5KEH57JQSQNWFXQ7A23G10';
$getDocument = $invoice->getDocument($documentUUID, 'pdf');

header("Content-type:application/pdf");            
echo $getDocument;


//$getDocument = $invoice->getDocument($documentUUID);
//print_r($getDocument);