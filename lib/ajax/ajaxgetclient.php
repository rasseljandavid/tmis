<?php

	include('../../config.php');

	$q = $_GET['q'];
	$clients = $db->selectObjects("clients","firstname like '%{$q}%' OR lastname like '%{$q}%' OR accountname like '%{$q}%'",null,10);
	
	$i = 0;
	foreach($clients as $client) {
		$client->image = $db->selectValue("images","filename","client_id={$client->id}");
		$j = 0;

		$data[$i]['id']   = $client->id;
		$data[$i]['name'] = $client->accountname;
		
		if($client->image) {
			$data[$i]['image'] = 'site/clients/' . $client->image;
		} else {
			$data[$i]['image'] = 'images/default.png';
		}
		
		
		$data[$i]['collector'] = getCollectorName($client->user_id);
		
		if(!$data[$i]['collector']) {
			$data[$i]['collector'] = "";
		}
		
		if(isset($_GET['t'])) {
			
			$loans = $db->selectObjects("loans", "client_id =" . $client->id . " AND paid = 0 AND active = 1", "loanDate DESC");
			foreach($loans as $loan) {
				$data[$i]['loans'][$j]['id'] 	   = $loan->id;
				$data[$i]['loans'][$j]['label']    = formatToAmount($loan->capital) . " Loan (" . date("F d, Y", strtotime($loan->loanDate)) . ")";
				$j++;
			}
		}
		
		$i++;
    }

	header('Content-Type: application/json');
	echo json_encode($data);
?>