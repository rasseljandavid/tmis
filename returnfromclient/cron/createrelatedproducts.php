<?php
	require_once('../exponent.php');
	$prod = new product();
	$products = $prod->find("all", true, false);
	$db->delete("crosssellItem_product");
	foreach($products as $item) {
		//if the category of the prodcut is parent
	

		if($item->storeCategory[0]->parent_id == 0 && isset($item->storeCategory[0]->parent_id)) {
		
			//Get random products
			$sql = "SELECT id FROM exponent_product WHERE id in (SELECT product_id  FROM exponent_product_storeCategories WHERE  storecategories_id = {$item->storeCategory[0]->id} AND product_id != {$item->id}) ORDER BY RAND() LIMIT 3";
			$related_ids = $db->selectObjectsBySql($sql);
			foreach($related_ids as $related_id) {
				$obj = "";
				$obj->crosssellItem_id  = $related_id->id;
				$obj->product_id 		= $item->id;
				$obj->product_type 	 	= "product";
				$db->insertObject($obj, "crosssellItem_product");
			}
		} else {
			//Child Category
			
			//Get the parent category
			$parent_cat = $db->selectObject("storeCategories", "id = {$item->storeCategory[0]->parent_id}");
			
			$sql = "SELECT id FROM exponent_storeCategories WHERE parent_id = {$parent_cat->id} ORDER BY RAND() LIMIT 3";
			$related_categories_ids = $db->selectObjectsBySql($sql);
			
			foreach($related_categories_ids as $related_categories_id) {
				$sql = "SELECT id FROM exponent_product WHERE id in (SELECT product_id  FROM exponent_product_storeCategories WHERE  storecategories_id = {$related_categories_id->id} AND product_id != {$item->id}) ORDER BY RAND() LIMIT 1";
				$related_ids = $db->selectObjectsBySql($sql);
				
				
				foreach($related_ids as $related_id) {
					$obj = "";
					$obj->crosssellItem_id  = $related_id->id;
					$obj->product_id 		= $item->id;
					$obj->product_type 	 	= "product";
					$db->insertObject($obj, "crosssellItem_product");
				}
			}
		}
	}
?>