<?php

//https://api.megaventory.com/v2/json/reply/documentGet?APIKEY=3b8db5d01e61d276@m513&mvDocumentStatus=verified&query=
//https://api.megaventory.com/v2/json/reply/documentGet?APIKEY=YOUR_API_KEY_HERE&mvDocumentStatus=verified

if(!empty($_POST['submit'])) {
	
	$from_date = date("Y-m-d 00:00:00", strtotime($_POST['date']));
	$to_date   = date("Y-m-d 23:59:59", strtotime($_POST['date']));

$arr2 = "";
$arr2['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
$arr2['ProductSKU'] = "";
$str2 = json_encode($arr2);



$ch2 = curl_init();
$headers2= array('Accept: application/json','Content-Type: application/json'); 
curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);
curl_setopt($ch2, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch2, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/ProductGet?format=json");
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS,$str2);


// receive server response ...
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

$server_output2 = curl_exec ($ch2);

$res2 = json_decode($server_output2);

curl_close ($ch2);
$products = array();
foreach($res2->mvProducts as $item) {
	@$products[$item->ProductSKU]->title = $item->ProductDescription;
	$products[$item->ProductSKU]->supplier = $item->ProductMainSupplierID;
	$products[$item->ProductSKU]->category = $item->ProductCategoryID;
}




$arr = "";
$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
$arr['mvDocumentStatus'] = "Verified";	
$arr['mvDocumentTypeAbbreviation'] = "GOSI";	
$arr['query'] = "mv.DocumentDate>=DATETIME'{$from_date}' AND mv.DocumentDate<=DATETIME'{$to_date}'";
	
$str = json_encode($arr);



$ch = curl_init();
$headers= array('Accept: application/json','Content-Type: application/json'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/DocumentGet?format=json");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

$res = json_decode($server_output);

$data = array();

foreach($res->mvDocuments as $item) {
	
	if($item->DocumentTypeAbbreviation == "GOSI") {
		foreach($item->DocumentDetails as $item2) {
			

			
			
			@$data[$products[$item2->DocumentRowProductSKU]->category][$products[$item2->DocumentRowProductSKU]->title] +=  $item2->DocumentRowQuantity;
		}
	}
}
$i = 1;

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	
	<script>
	  $(function() {
	    $( "#datepicker" ).datepicker();
	  });
	  </script>
</head>
<body>

	<form action="sample.php" method="post" class="hidden-print">
		<input type="text" name="date" id="datepicker" />
		<input type="submit" value="Submit" name="submit" />
	</form>
	<br />
	<?php if(!empty($_POST['submit'])) : ?>
		<h1>Summary of Products to be Pickup for <?php echo $_POST['date']; ?></h1>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<td></td>
				<td>Product</td>
				<td>Quantity</td>
			</tr>
		</thead>
		<tbody>
			<?php foreach($data as $data3) : ?>
			<?php foreach($data3 as $key => $item) : ?>
				<tr>	
					<td><?php echo $i++; ?>. </td>
					<td><?php echo $key; ?></td>
					<td><?php echo $item; ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php endif; ?>
</body>
</html>