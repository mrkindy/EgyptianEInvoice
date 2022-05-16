<?php
/*
 * This file is part of the Kindy\EgyaptianEInvoice Package.
 *
 * (c) Ibrahim Abotaleb <admin@mrkindy.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../vendor/autoload.php";
require_once "./config.php";

$eta = new \Kindy\EgyaptianEInvoice\ETA($config['client_id'],$config['client_secret'], 'uat');

$getCode = $eta->getCode('EGS',"EG-500100200-001");
            
var_dump($getCode);