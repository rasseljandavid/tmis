<?php

	include('../../config.php');

	$q = $_GET['term'];
	$clients = $db->selectObjects("debtors","name like '%{$q}%'",null,10);

	$json = '[';
    $first = true;
      
	foreach($clients as $client) {
   		if (!$first) { $json .=  ','; } else { $first = false; }
      	$json .= '{"label":"'.$client->name.'", "value":"'.$client->id.'"}';
    }

	$json .= ']';
    echo $json;
	
?>