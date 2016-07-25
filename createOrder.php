<?php


$products = array();
$products[0]->SalesOrderRowProductSKU = "nescafe3in1twinpack";
$products[0]->SalesOrderRowProductDescription = "Nescafe 3in1 Original Twin Pack 10s/28";
$products[0]->SalesOrderRowQuantity = "2";
$products[0]->SalesOrderRowShippedQuantity = "2";
$products[0]->SalesOrderRowInvoicedQuantity = "2";
$products[0]->SalesOrderRowUnitPriceWithoutTaxOrDiscount = "79.23";
$products[0]->SalesOrderRowTaxID = "0";
$products[0]->SalesOrderTotalTaxAmount = "0";
$products[0]->SalesOrderRowDiscountID = "0";
$products[0]->SalesOrderRowTotalDiscountAmount = "0";
$products[0]->SalesOrderRowTotalAmount = "158.46";


date_default_timezone_set("Asia/Manila");
$dt = date("Y-m-d h:i:s");


$data  = "";
$data->SalesOrderId = "20355";
$data->SalesOrderNo = "2442";
$data->SalesOrderClientID           = "107";
$data->SalesOrderInventoryLocationID      = "2";

$data->SalesOrderDetails = $products;
$data->SalesOrderStatus = "Closed";



$arr = "";
$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
$arr['mvSalesOrder']  = $data;
$arr['mvRecordAction']  = "Update";

$str = json_encode($arr);


$ch = curl_init();
$headers = array('Accept: application/json','Content-Type: application/json'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_URL,"http://apitest.megaventory.com/json/reply/SalesOrderUpdate?format=json");
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