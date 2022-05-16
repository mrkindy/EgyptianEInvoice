# Kindy Egyptian EInvoicing PHP SDK

PHP Package for Egyptian eInvoicing SDK, eInvoicing is the solution of the Egyptian Tax Authority used by taxpayers to register their issued documents with the Tax Authority

## Install

Via Composer

``` bash
$ composer require kindy/egyptian-e-invoice
```

## Examples

Please see [Examples Files](examples).

## Usage

To view all example please vist [Examples Files](examples).
``` php

require_once "../vendor/autoload.php";
require_once "./config.php";

use Kindy\EgyaptianEInvoice\Document\Document;
use Kindy\EgyaptianEInvoice\Document\DocumentInvoiceLine;
use Kindy\EgyaptianEInvoice\ETAInvoice;

$issuer = [
    
    "address" => [
        "branchID" => "0",
        "country" => "EG",
        "governate" => "Cairo",
        "regionCity" => "Nasr City",
        "street" => "580 Clementina Key",
        "buildingNumber" => "Bldg. 0",
        "postalCode" => "68030",
        "floor" => "1",
        "room" => "123",
        "landmark" => "7660 Melody Trail",
        "additionalInformation" => "beside Townhall"
    ],
    "type" => "B",
    "id" => "500100200",
    "name" => "Issuer Company"
];

$receiver = [
    "address" => [
        "country" => "EG",
        "governate" => "Cairo",
        "regionCity" => "Nasr City",
        "street" => "580 Clementina Key",
        "buildingNumber" => "Bldg. 0",
    ],
    "type" => "B",
    "id" => "674859545",
    "name" => "Recievr Company"
];
$document = new Document();
$document->setIssuer($issuer)
         ->setReceiver($receiver)
         ->setDateTimeIssued('2022-05-13T12:35:00Z');
$document->setPurchaseOrderReference('3asd1as');


$invoiceLine = new DocumentInvoiceLine();
$invoiceLine->setDescription('Software ERP')
        ->setInternalCode('MA123')
        ->setItemType('EGS')
        ->setItemCode('EG-500100200-001')
        ->setUnitType('JOB')
        ->setQuantity(2)
        ->setUnitValue('EGP',985)
        ->setDiscount(137.9,'A')
        ->setTaxableItems('T1',256.494,14,'V001')
        ->setItemsDiscount(71.804)
        ->setTotal();

$document->setInvoiceLine($invoiceLine);

$invoiceLine = new DocumentInvoiceLine();
$invoiceLine->setDescription('Software CRM')
        ->setInternalCode('CRM123')
        ->setItemType('EGS')
        ->setItemCode('EG-500100200-001')
        ->setUnitType('JOB')
        ->setQuantity(1)
        ->setUnitValue('EGP',600)
        ->setTaxableItems('T1',84,14,'V001')
        ->setTotal();
        
$document->setInvoiceLine($invoiceLine);

$document->setInternalID('103333')->setTaxpayerActivityCode('6920')->calculate();
if(isset($_POST['signature']))
{
    $document->setSignatures(
        [
            [
                'signatureType' => 'I',
                'value' => $_POST['signature']
            ]
        ]
    );

    $invoice = new ETAInvoice($config['client_id'],$config['client_secret'], 'uat');
    $finalDocument = [$document->toArray()];
    $documentSubmit = $invoice->submitDocument($finalDocument);
    echo $documentSubmit->acceptedDocuments[0]->uuid.'-'.$documentSubmit->acceptedDocuments[0]->longId.'-'.$documentSubmit->acceptedDocuments[0]->internalId;
}

?>
<button id="sign" type="button">Sign</button>
<button id="sendToEta" type="button" style="display: none;">Send To Eta</button>
<div id="result"></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      
<script type = "text/javascript">
    
    var signature;
    var socket = new WebSocket("ws://localhost:18088");

    $("#sign").click(function(){
        ConnectToSignatureServer();
    });


    $("#sendToEta").click(function(){
        $.ajax({
            type: "POST",
            url: "",
            data: {signature:signature},
            success: function(data){
                $("#result").html('Document Sent To Eta');
            }
        });
    })

    function ConnectToSignatureServer() {
    
        socket.send('{Document:\'<?php echo $document->getSerialize()?>\',TokenCertificate:\'Egypt Trust Sealing CA\',Password:\'15775108\'}');
        
        
        socket.onmessage = function (response) { 
            var responseObj = JSON.parse(response.data);

            if(responseObj.cades != 'NO_SOLTS_FOUND' && responseObj.cades != 'PASSWORD_INVAILD' && responseObj.cades != 'CERTIFICATE_NOT_FOUND' && responseObj.cades != 'NO_DEVICE_DETECTED')
            {
                $("#sendToEta").show();
                $("#result").html('Document Signed');
                signature = responseObj.cades;
            }else{
                $("#result").html(responseObj.cades);
            }
        };
    }

    socket.onclose = function() { 
        $("#result").html('Connection is closed');
    };

    socket.onerror = function() { 
        $("#result").html('Connection Error');
    };
    socket.onopen = function() { 
        $("#result").html('Connection Open');
    };
</script>
```
## Integrate with eSign token
I have developed a small tool that you can use to sign invoices through WebSocket before send it to EAT, you can find this tool on [Egyptian Tax EInvoice HttpSignature](https://github.com/mrkindy/ETAHttpSignature)

## Contributing

Your contribution is welcome

## Credits

- [Ibrahim Abotaleb](https://github.com/mrkindy)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
