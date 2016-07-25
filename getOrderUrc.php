<?php

function getWholeOrders($qty, $perbox) {
	$x = $qty / $perbox;
	$temp = explode(".", $x);
	$temp[1] = substr($temp[1], 0, 1);
	if($temp[1] >= 5) {
		return (int) $temp[0] + 1;
	} else {
		return (int) $temp[0]; 
	}
}


if($_POST['submit']) {
	
	if (!empty($_FILES['file']['error'])) {
        echo 'There was an error uploading your file.  Please try again.';
        exit();
    }

    $file = '';
    $file = $_FILES['file']['tmp_name'];

    $checkhandle = fopen($file, "r");
    $header = fgetcsv($checkhandle, 10000, ",");
	$supplierKey = '';
    while (($data = fgetcsv($checkhandle, 10000, ",")) !== FALSE) {
		$array = "";
		if(!empty($data[0])) {
			//Next Supplier
			$supplierKey = $data[0];
		} else {
				$array[] = $data[1];
				$array[] = $data[2];
				$array[] = $data[3];
				$array[] = $data[4];
				$myData[$supplierKey][] = $array;
			
		}
		
 
    }

    fclose($checkhandle);
	



//Product Name
//Total Inventory
//Total Stock Alertas $key => $value)
$grandTotal = 0;
foreach($myData as $key => $myArray) {
for($i=0; $i< count($myArray); $i++) {
	$temp = explode("/", $myArray[$i][0]);
	$myArray[$i][4] = $temp[count($temp) - 1];

}

$orders = array();

$totalCase = 0;
$total = 0;

foreach($myArray as $item) {

	//If the inventory is less than the alert level
	$item[1] = str_replace(",","",$item[1]);
	$item[2] = str_replace(",","",$item[2]);
	if($item[1] < $item[2]) {
		$qty = $item[2] - $item[1];
		
		if($item[0] <> "") {
			$numOfBox = getWholeOrders($qty, $item[4]);
		
			if($numOfBox > 0) {

			$order['product'] = $item[0];
			$order['qty'] = $numOfBox . 'cs';
			$order['subtotal'] = $item[3] * $item[4] * $numOfBox;
			$orders[] = $order;
			$totalCase += $numOfBox;
			$total += $item[3] * $item[4] * $numOfBox;
			
	
			}
		}
	}


}


if($totalCase != 0) {
echo "<h2>{$key}</h2>";
foreach($orders as $item) {
	echo $item['qty'] . " of " . $item['product'] . " " . $item['subtotal'] . "<br />";
	
	
}
echo "<strong>Total Case: {$totalCase}</strong><br />";
echo "<strong>Total Amount: " . number_format($total, 2) . "</strong><br /><br />";
}

$grandTotal += $total; 
echo "Total Order is: " . number_format($grandTotal,2);
}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>
</head>
<body>
	<form action="getOrder.php" method="post" enctype="multipart/form-data">
		Upload CSV File: <input type="file" name="file" id="file"> <br /><br />
		<input type="submit" name="submit" value="Calculate Purchase Order" />
	</form>
</body>
</html>