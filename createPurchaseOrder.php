<?php
exit();

$products = array();
$products[0]->PurchaseOrderRowProductSKU = "14800016057073";
$products[0]->PurchaseOrderRowQuantity = "10";
$products[0]->PurchaseOrderRowUnitPriceWithoutTaxOrDiscount = "175.10708";
$products[0]->PurchaseOrderRowDiscountID = "3";



date_default_timezone_set("Asia/Manila");
$dt = date("Y-m-d h:i:s");


$data  = "";
$data->PurchaseOrderId = "";
$data->PurchaseOrderNo = "";
$data->PurchaseOrderReferenceNo = "";
$data->PurchaseOrderReferenceApplication = "";
$data->PurchaseOrderDate = "DATETIME'{$dt}'";
$data->PurchaseOrderCustomOrderDate1 = "";
$data->PurchaseOrderCustomOrderDate2 = "";
$data->PurchaseOrderCurrencyCode     = "PHP";
$data->PurchaseOrderSupplierID           = "102";
$data->PurchaseOrderAddress     = "";
$data->PurchaseOrderPickupAddress    = "";
$data->PurchaseOrderContactPerson      = "";
$data->PurchaseOrderInventoryLocationID      = "2";
$data->PurchaseOrderComments      = "";
$data->PurchaseOrderTags      = "";
$data->PurchaseOrderTotalQuantity      = "10";
$data->PurchaseOrderAmountSubtotalWithoutTaxAndDiscount      = "0";
$data->PurchaseOrderAmountTotalTax      = "0";
$data->PurchaseOrderAmountGrandTotal      = "1733.56";
$data->PurchaseOrderDetails = $products;
$data->PurchaseOrderStatus = "Verified";



$arr = "";
$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
$arr['mvPurchaseOrder']  = $data;
$arr['mvRecordAction']  = "Insert";

$str = json_encode($arr);


$ch = curl_init();
$headers = array('Accept: application/json','Content-Type: application/json'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_URL,"http://apitest.megaventory.com/json/reply/PurchaseOrderUpdate?format=json");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

$res = json_decode($server_output);

echo "<pre>";;
print_r($res);

exit();
curl_close ($ch);

?>