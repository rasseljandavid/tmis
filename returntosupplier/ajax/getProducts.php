<?php
global $router, $db;
include_once('../exponent.php');
$products_obj = $db->selectObjects("product");
$products = "";
$i = 0;
foreach($products_obj as $item) {
	@$products[$i]['id'] = $item->id;
	$products[$i]['title'] = $item->title;
	$products[$i]['sku'] = $item->model;
	$products[$i]['price'] = $item->special_price;
	$i++;
}

echo json_encode($products);
	
?>