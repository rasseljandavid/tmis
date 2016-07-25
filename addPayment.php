<?php
	//Connect to DB
	include('config.php');

	//If submitted insert new payment and redirect to the previous page
	if(isset($_POST['submit_payment'])) {
		$result = 0;
		
		//Get the client to get the client 
		$user_id = $db->selectValue("clients", "user_id", "id = {$_POST['client_id']}");
	
		//Hardcoded for now
		if(empty($user_id)) {
			$user_id = 8;
		}
		$dateSelected = date("F d, Y", strtotime($_POST['paymentDate']));
	
	
	
		$loan_verified = $_POST['capital'];

		
		if($loan_verified) {
		
			if(isset($_POST['amount'])) {
				$_POST['capital']  = $POST['capital'];
				$_POST['interest'] = 0;
			}

			if(!empty($_POST['payment_id'])) {
			
				$payment = $db->selectObject("payments", "id = {$_POST['payment_id']} AND active = 1");

				$payment->capital        = $_POST['capital'];
				$payment->interest       = $_POST['interest'];
				$payment->loan_id        = 0;
				$payment->client_id      = $_POST['client_id'];
				$payment->paymentDate    = date('Y-m-d H:i:s', strtotime($_POST['paymentDate']));
				$payment->remarks        = $_POST['remarks'];
			
				$res = $db->updateObject($payment, "payments");
			} else {
				
				$payment = new stdclass();
				$payment->capital        = $_POST['capital'];
				$payment->interest       = $_POST['interest'];
				$payment->loan_id        = 0;
				$payment->client_id      = $_POST['client_id'];
				$payment->paymentDate    = date('Y-m-d H:i:s', strtotime($_POST['paymentDate']));
				$payment->remarks        = $_POST['remarks'];
				$payment->created  		 = time();
				$payment->createdBy  	 = $_SESSION['ID'];
				$payment->active 		 = 1;

				$res = $db->insertObject($payment, "payments");
			}
			$alert = new stdclass();
			if($res) {
				$alert->type = "success";
				$alert->msg  = "You have successfully added " . formatToAmount($payment->capital + $payment->interest) . " payment for " . getClientName($payment->client_id) . ".";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}
		
		
			$_SESSION['ALERT'][] = $alert;
		}
		
		back();
	}
	
?>