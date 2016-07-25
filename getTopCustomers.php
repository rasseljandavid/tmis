<?php
$so = getSalesOrder();

$customers_arr = getCustomers();
$customers = array();

foreach($customers_arr as $item) {
	$customers[$item->SupplierClientID] = $item->SupplierClientName;
}

$data = array();
$total_book = 0;
$total_sale = 0;
foreach($so as $item) {
if(($item->SalesOrderStatus == "Closed" || $item->SalesOrderStatus == "FullyInvoiced" || $item->SalesOrderStatus == "PartiallyShippedAndPartiallyInvoiced")) {
    foreach($item->SalesOrderDetails as $item2) {

        @$data[$item->SalesOrderClientID]->sale +=  $item2->SalesOrderRowUnitPriceWithoutTaxOrDiscount * $item2->SalesOrderRowShippedQuantity;
        @$data[$item->SalesOrderClientID]->book +=  $item2->SalesOrderRowTotalAmount;
		$total_sale +=  $item2->SalesOrderRowUnitPriceWithoutTaxOrDiscount * $item2->SalesOrderRowShippedQuantity;
		$total_book +=  $item2->SalesOrderRowTotalAmount;
    }
}

}


arsort ($data);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<h1 style="text-align: center;">Top Customers for the Current Month</h1>
<table class='table table-striped table-bordered' style="width: 80%; margin: auto; margin-bottom: 20px;">
	<tr>
		<th>Customers</th>

		<th>Total Sale</th>
    
		
		 <th>Total Book</th>
	</tr>

<?php foreach($data as $key => $item) : ?>
	<tr>
		<td><?php echo ++$i . ". " . $customers[$key]; ?></td>

	<td>P<?php echo number_format($item->sale, 2); ?></td>
<td>P<?php echo number_format($item->book, 2); ?></td>
	
		
	
	</tr>
<?php endforeach; ?>
	
	<tr>
		<th>Total</th>

		<th>P<?php echo number_format($total_sale, 2); ?></th>
		<th>P<?php echo number_format($total_book, 2); ?></th>
	</tr>
</table>
</body>
</html>


<?php
function getSalesOrder() {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['query']  = "mv.SalesOrderDate>=DATETIME'2015-09-01 00:00:00' AND mv.SalesOrderDate<=DATETIME'2015-09-30 23:59:59'";
	$str = json_encode($arr);


	$ch = curl_init();
	$headers = array('Accept: application/json','Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SalesOrderGet?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	$res = json_decode($server_output);

	curl_close ($ch);
	
	return $res->mvSalesOrders;
}	

function getCustomers() {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['query'] = "mv.SupplierClientType = 2";
	$str = json_encode($arr);


	$ch = curl_init();
	$headers = array('Accept: application/json','Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SupplierClientGet?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	$res = json_decode($server_output);

	curl_close ($ch);
	
	return $res->mvSupplierClients;
}