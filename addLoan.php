<?php
	//Connect to DB
	include('config.php');

	//If submitted insert new loans and redirect to the loan page
	if(isset($_POST['submit']) || isset($_POST['submit_loan'])) {

		//Loan check if we are editing or adding
		if(!empty($_POST['loan_id'])) {
			$loan = $db->selectObject("loans", "id = {$_POST['loan_id']} AND active = 1");
	
			$loan->capital        = $_POST['capital'];
			$loan->interest       = $_POST['interest'];
			$loan->monthsToPay    = $_POST['monthstopay'];
			$loan->category_id    = $_POST['category_id'];
			$loan->loanDate       = date('Y-m-d H:i:s', strtotime($_POST['loanDate']));
			$loan->remarks        = $_POST['remarks'];
		
			$res = $db->updateObject($loan, "loans");
			
			$alert = new stdclass();

			if($res) {
				$alert->type = "success";
				$alert->msg  = "You have successfully updated " . formatToAmount($loan->capital) . " loan for " . getClientName($loan->client_id) . ".";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}

			$alerts[] = $alert;
			$_SESSION['ALERT'] = $alerts;
			
		} else {
			$loan = new stdclass();
			$loan->capital	    	= $_POST['capital'];
			$loan->interest     	= $_POST['interest'];
			$loan->client_id    	= $_POST['client_id'];
			$loan->category_id  	= $_POST['category_id'];
			$loan->loanDate 		= date( 'y-m-d', strtotime($_POST['loanDate']));
			$loan->monthsToPay  	= $_POST['monthstopay'];
			$loan->paid     		= 0;
			$loan->remarks      	= $_POST['remarks'];
			$loan->created  		= time();
			$loan->createdBy  		= $_SESSION['ID'];
			$loan->active	    	= 1;

			$res = $db->insertObject($loan, "loans");
			
			$alert = new stdclass();

			if($res) {
				$alert->type = "success";
				$alert->msg  = "You have successfully added " . formatToAmount($loan->capital) . " loan for " . getClientName($loan->client_id) . ".";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}

			$alerts[] = $alert;
			$_SESSION['ALERT'] = $alerts;
		}
	
		back();
	}


?>