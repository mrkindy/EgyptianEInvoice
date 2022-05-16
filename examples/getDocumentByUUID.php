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

$eta = new \Kindy\EgyaptianEInvoice\ETA($config['client_id'],$config['client_secret'], 'uat');

$documentUUID = 'AXJE5KEH57JQSQNWFXQ7A23G10';
$getDocument = $eta->getDocument($documentUUID, 'pdf');

header("Content-type:application/pdf");            
echo $getDocument;


//$getDocument = $eta->getDocument($documentUUID);
//print_r($getDocument);