<?php
global $router, $db;
include_once('../exponent.php');
$companies_obj = $db->selectObjects("companies");
$companies = "";
$i = 0;
foreach($companies_obj as $item) {
	@$companies[$i]['id'] = $item->id;
	$companies[$i]['title'] = $item->title;
	$i++;
}

echo json_encode($companies);
	
?>