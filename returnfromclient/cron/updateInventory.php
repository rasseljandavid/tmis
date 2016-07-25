<?php
	global $router, $db;
	include_once('../exponent.php');
	/*
	
	

*/
$inv = getInventory();

foreach($inv as $item) {  
	
	/*
	echo $item->productID . " = Product id <br />";
	echo $item->StockOnHandTotal . " = Inventory <br />";
	exit();
	*/
	$sc = $db->selectObject("product", "id = {$item->productID}");

	//Record already exist
	if(!empty($sc->id)) {
		$sc->quantity    = $item->StockPhysicalTotal;
		$db->updateObject($sc, "product");
	} 
	/*
	echo "<pre>";
	print_r($rec);
	exit();
	$sc = new storeCategory();
	$sc->id       = $cat->ProductCategoryID;
	$sc->title    = $cat->ProductCategoryName;
	$sc->is_active = 1;
	$sc->sef_url = $router->encode($sc->title);
	$sc->save();
	*/
}

	function getInventory($productID = "", $includeReferencedObjects = false, $ShowOnlyProductsWithPositiveQty = false, $ShowOnlyProductsThanNeedToBeOrdered = false) {
		$arr = "";
		$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
		$arr['includeReferencedObjects'] = $includeReferencedObjects;
		$arr['ShowOnlyProductsWithPositiveQty'] = $ShowOnlyProductsWithPositiveQty;
		$arr['ShowOnlyProductsThanNeedToBeOrdered'] = $ShowOnlyProductsThanNeedToBeOrdered;
	
		$query = "";
		if($productID != "") {
			$query = "mv.ProductID = {$productID}";
		}
		$arr['query'] = $query;

		$str = json_encode($arr);

		$ch = curl_init();
		$headers= array('Accept: application/json','Content-Type: application/json'); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/InventoryLocationStockGet?format=json");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
	
		$res = json_decode($server_output);
	
		curl_close ($ch);

		return $res->mvProductStockList;
	}
	
	function getCategories() {
		$arr = "";
		$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
		$arr['query'] = "mv.ProductCategoryID != 0 ORDER BY mv.ProductCategoryName";
		$str = json_encode($arr);


		$ch = curl_init();
		$headers = array('Accept: application/json','Content-Type: application/json'); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/ProductCategoryGet?format=json");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);

		$res = json_decode($server_output);

		curl_close ($ch);

		return $res->mvProductCategories;
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
?>