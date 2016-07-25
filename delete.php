<?php
	//Connect to DB
	include('config.php');
	
	$type = $_GET['type'];
	$id   = $_GET['id'];
	
	if($type == 'loans') {
		$loan = $db->selectObject("loans", "id = {$id}");
		$loan->active = 0;

		$res = $db->updateObject($loan, "loans");
	
		$alert = new stdclass();
		if($res) {

			$alert->type = "success";
			$alert->msg  = "You have successfully deleted " . formatToAmount($loan->capital) . " loan of " . getClientName($loan->client_id) . ".";
		} else {
			$alert->msg  = "Oops, something went wrong. Please try again.";
		}
		
		$alerts[] = $alert;
		$_SESSION['ALERT'] = $alerts;

		back();
			
	} elseif($type == 'payment') {
		$payment = $db->selectObject("payments", "id = {$id}");
		$payment->active = 0;
		
		$res = $db->updateObject($payment, "payments");
		paidLoan($payment->loan_id, 0);
		
		$alert = new stdclass();
		if($res) {
			$alert->type = "success";
			$alert->msg  = "You have successfully deleted " . formatToAmount($payment->capital + $payment->interest) . " payment of " . getClientName($payment->client_id) . ".";
		} else {
			$alert->msg  = "Oops, something went wrong. Please try again.";
		}
		
		$alerts[] = $alert;
		$_SESSION['ALERT'] = $alerts;
		
		back();
		
	} elseif($type == 'notes') {
		$res = deleteNote($id);
		
		back();
	}
	
?>