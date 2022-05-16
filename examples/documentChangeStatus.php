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
$data = [

	"status" => "cancelled",
	"reason" => "Added by mistake"
];
$documentUUID = 'AXJE5KEH57JQSQNWFXQ7A23G10';
$documentChangeStatus = $invoice->documentChangeStatus($documentUUID,$data);
print_r($documentChangeStatus->status);