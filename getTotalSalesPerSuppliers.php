<?php

$products = getProducts();
$prod_suppliers = array();
foreach($products as $item) {
	$prod_suppliers[$item->ProductSKU] = $item->ProductMainSupplierID;
}
$customers_arr = getCustomers();
$customers = array();

foreach($customers_arr as $item) {
	$customers[$item->SupplierClientID] = $item->SupplierClientName;
}


$suppliers_arr = getSuppliers();
$suppliers = array();

foreach($suppliers_arr as $item) {
	if(!empty($item->SupplierClientComments)) {
		$suppliers[$item->SupplierClientID] = $item->SupplierClientName;
	}
}



$arr = getSalesOrder();


$data = array();

foreach($arr as $item) {  
	if(($item->SalesOrderStatus == "Closed" || $item->SalesOrderStatus == "FullyInvoiced" || $item->SalesOrderStatus == "PartiallyShippedAndPartiallyInvoiced") && $item->SalesOrderContactPerson != "") {
		
		foreach($item->SalesOrderDetails as $item2) {
			
			$prod_supplier = $suppliers[$prod_suppliers[$item2->SalesOrderRowProductSKU]];
			
			@$data[$item->SalesOrderContactPerson][$prod_supplier] += $item2->SalesOrderRowUnitPriceWithoutTaxOrDiscount * $item2->SalesOrderRowShippedQuantity;
			@$data[$item->SalesOrderContactPerson]['total'] += $item2->SalesOrderRowUnitPriceWithoutTaxOrDiscount * $item2->SalesOrderRowShippedQuantity;
		
		
		}
		
		
	
	}
}

foreach($data as $key => $item) {
	$max = 0;
	$min = "";
	foreach($item as $key2 => $item2) {
		if($max <= $item2 && $key2 != "total") {
			$max = $item2;
		}
		if($min > $item2 || $min == "") {
			$min = $item2;
		}
		$data[$key]['min'] = $min;
		$data[$key]['max'] = $max;
	}
}
$supps = array();
foreach($suppliers  as $supplier) {
	foreach($data as $key => $item) {
		$supps[$supplier] += $item[$supplier];
	}
}
asort($supps);
$supps = array_reverse($supps);

	
$suppliers = $supps;
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
	<h2 style="text-align: center;">Total Sales Per Supplier</h2>
<table class='table table-striped table-bordered' style="width: 80%; margin: auto; margin-bottom: 20px;">
	<tr>
		<th>Supplier</th>

		<?php foreach($data as $key => $item) : ?>
			<th>
				<?php echo $key; ?>
			</th>
		<?php endforeach; ?>
		<th>Total</th>
		
		
	</tr>

<?php foreach($suppliers as  $key2 => $supplier) : ?>
	<tr>
		<td><?php echo $key2; ?></td>
	

		<?php foreach($data as $key => $item) : ?>
			
		<td>
			<?php 
			if($item[$key2] == $item['max']) {
				echo "<span style='color:blue'>";
			}
			if($item[$key2] == $item['min']) {
				echo "<span style='color:red'>";
			}
			echo "P".number_format($item[$key2], 2); 
			if($item[$key2] == $item['max'] || $item[$key2] == $item['min']) {
				echo "</>";
			}
			?>
		</td>
		<?php endforeach; ?>
		<td>P<?php echo number_format($supplier, 2); ?></td>
	
	</tr>
<?php endforeach; ?>
	<tr>
		<th>Total</th>
		<?php foreach($data as $key => $item) : ?>
		
		<th>
			P<?php echo number_format($item['total'], 2); ?>
		</th>
		<?php endforeach; ?>
		<th>
			P<?php echo number_format(($data['Jason Mercado']['total'] + $data['El Ramos']['total'] +$data['Norman Bognot']['total']), 2); ?>
		</th>
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


function getSalesOrderByID($sid) {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['mvSalesOrderNo'] = $sid;
	//$arr['query']  = "mv.SalesOrderStatus = 'FullyInvoiced'";
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

function convertDate($date) {
//	$date = '/Date(1440190800000-0000)/'; 
	preg_match('/(\d{10})(\d{3})([\+\-]\d{4})/', $date, $matches); 

	// Get the timestamp as the TS tring / 1000 
	$ts = (int) $matches[1]; 

	// Get the timezone name by offset 
	$tz = (int) $matches[3]; 
	$tz = timezone_name_from_abbr("", $tz / 100 * 3600, false); 
$tz= "Asia/Manila";
	$tz = new DateTimeZone($tz); 

	// Create a new DateTime, set the timestamp and the timezone 
	$dt = new DateTime(); 
	$dt->setTimestamp($ts); 
	$dt->setTimezone($tz); 

	// Echo the formatted value  
	return $dt->format('Y-m-d');  
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

function getSuppliers() {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['query'] = "mv.SupplierClientType = 1";
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

function getProducts($productID = "", $includeReferencedObjects = false) {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['includeReferencedObjects'] = $includeReferencedObjects;
	$query = "";
	if($productID != "") {
		$query = "mv.ProductID = {$productID}";
	}
	$arr['query'] = $query;
	$str = json_encode($arr);


	$ch = curl_init();
	$headers = array('Accept: application/json','Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/ProductGet?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	$res = json_decode($server_output);
	
	curl_close ($ch);
	return $res->mvProducts;
}