<?php
	require_once('../exponent.php');
	global $db, $router;
	//Fix the category url
	$cats = $db->selectObjects("storeCategories");
	foreach($cats as $item) {
		$cat = new storeCategory($item->id);
		$cat->sef_url = $router->encode($cat->title);
		$cat->update();
	}
	
	
	//Fix the product url
	$products = $db->selectObjects("product");
	foreach($products as $item) {
		$prod = $db->selectObject("product","id = {$item->id}");
		$prod->sef_url = $router->encode($prod->title . " " . $prod->capacity);
		$db->updateObject($prod, "product");
	}
	
?>