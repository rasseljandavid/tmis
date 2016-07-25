<?php
	include('../../config.php');

	$dateSelected = date("F d, Y", strtotime($_POST['dateSelected']));
	$user_id      = $_POST['user_id'];
	
	$sql = "SELECT client_id, (capital + interest) amount FROM payments WHERE client_id in (SELECT id FROM clients WHERE user_id = {$user_id} AND active = 1) AND active = 1 AND DATE_FORMAT(paymentDate, '%M %d, %Y') = '{$dateSelected}' ORDER BY paymentDate desc";
	$payments = $db->selectObjectsBySql($sql);

	$data = array();
	foreach($payments as $item) {
		$data['id_' . $item->client_id] = number_format($item->amount,0,".","");
	}
	
	


	if(!empty($data)) {
	
		echo json_encode($data);
	} else {
		echo "";
	}

?>