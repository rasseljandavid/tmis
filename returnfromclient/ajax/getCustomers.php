<?php

global $router, $db;
include_once('../exponent.php');
$customers_obj = $db->selectObjects("customers");



$customers = "";
$i = 0;
foreach($customers_obj as $item) {
	@$customers[$i]['id'] = $item->id;
	$customers[$i]['name'] = $item->cust_name;
	$i++;
}

echo json_encode($customers);
	
?>