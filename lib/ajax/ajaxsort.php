<?php

	include('../../config.php');
	
	foreach ($_GET['listItem'] as $position => $item) {
		
		$client           = $db->selectObject("clients", "id = {$item}");

		$client->position = $position; 
		
		$result = $db->updateObject($client, "clients");

	}
	
	$alert = new stdclass();
	
	if($result) {
		$alert->type = "success";
		$alert->msg = "Client's positions are now updated.";
	} else {
		$alert->msg = "Error occurred while processing your request. Please try again.";
	}
	$alerts[] = $alert;

	
	echo showAlerts($alerts);
?>