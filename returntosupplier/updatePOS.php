<?php

	include_once('exponent.php');
	global $router, $db;
	

	$suppliers = getSuppliers();
	
	foreach($suppliers as $supplier) {    
		if($supplier->SupplierClientName != "Main Warehouse") {
			$sc = $db->selectObject("companies", "title ='{$supplier->SupplierClientName}'");
			//Record already exist
			if(!empty($sc->id)) {
				$sc->title    = $supplier->SupplierClientName;
				$db->updateObject($sc, "companies");
			} else {
				$sc = "";
				$sc->id       = $supplier->SupplierClientID;
				$sc->title    = $supplier->SupplierClientName;
				$db->insertObject($sc, "companies");
			}
		}
	}
	

	$db->delete("pos");
	
	$pos = getSalesOrder();
	
	
	foreach($pos as $item) {
	
		if($item->SalesOrderComments == "POS") {
			$str = json_encode($item);
			@$obj->sale_order = $str;
			$db->insertObject($obj, "pos");
		}
	}
	
	$test = $db->countObjects("pos");
	
	
	$customers = getCustomers();
	
	foreach($customers as $cus) {    
		
		$sc = $db->selectObject("customers", "title ='{$cus->SupplierClientName}'");
		
		if(!empty($sc->id)) {

			$sc->id           = $cus->SupplierClientID;
			$sc->cust_name    = $cus->SupplierClientName;
			
			$db->updateObject($sc, "customers");
		} else {
			$sc = "";
			$sc->id       = $cus->SupplierClientID;
			$sc->cust_name    = $cus->SupplierClientName;
			
			//Record al
			
			$db->insertObject($sc, "customers");
		}
	}
	
	
	

$cats = getCategories();

foreach($cats as $cat) {    
	
	$sc = $db->selectObject("storeCategories", "title ='{$cat->ProductCategoryName}'");
	//Record already exist
	if(!empty($sc->id)) {
		$sc->title    = $cat->ProductCategoryName;
		$sc->sef_url = $router->encode($sc->title);
		$db->updateObject($sc, "storeCategories");
	} else {
		$sc = "";
		$sc->id       = $cat->ProductCategoryID;
		$sc->title    = $cat->ProductCategoryName;
		$sc->is_active = 1;
		$sc->sef_url = $router->encode($sc->title);
		$db->insertObject($sc, "storeCategories");
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

$products = getProducts(null, true);

$db->delete("product_storeCategories", "1=1");

foreach($products as $item) {    
	
	$sc = $db->selectObject("product", "id ={$item->ProductID}");
	
	
	if(!empty($sc->id)) {
		$sc->title   = $item->ProductDescription;
		$sc->body    = $item->ProductLongDescription;
		$sc->sef_url = $router->encode($sc->title);
		$sc->model   = $item->ProductSKU;
		$sc->base_price   = $item->ProductSellingPrice * 1.05;
		$sc->special_price   = $item->ProductSellingPrice;
		$sc->use_special_price   = 1;
		$sc->product_type   = "product";
		$sc->manufacturing_price = $item->ProductMainSupplierPrice;
		$db->updateObject($sc, "product");
	} else {
		$sc = "";
		$sc->id      = $item->ProductID;
		$sc->title   = $item->ProductDescription;
		$sc->body    = $item->ProductLongDescription;
		$sc->sef_url = $router->encode($sc->title);
		$sc->model   = $item->ProductSKU;
		$sc->base_price   = $item->ProductSellingPrice * 1.05;
		$sc->special_price   = $item->ProductSellingPrice;
		$sc->use_special_price   = 1;
		$sc->product_type   = "product";
		$sc->manufacturing_price = $item->ProductMainSupplierPrice;
		$db->insertObject($sc, "product");
	}
	
	
	
	
	$obj = "";
	$obj->storecategories_id = $item->ProductCategoryID;
	$obj->product_id         = $item->ProductID;
	$obj->product_type       = "product";
	$db->insertObject($obj, "product_storeCategories");
}

//	echo "<p>Done updating POS. Total POS are {$test}</p>";
header("location: index.php?update=1");
	function getSalesOrder() {
		$arr = "";
		$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";
		$arr['mvSalesOrderStatus'] = "Verified";		
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

?>