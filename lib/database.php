<?php

	//All functions are ordered alphabetically and named as verb

	function addToTrashCan($id, $tablename) {
		global $db;
		
		$trash = new stdclass();
		$trash->tablename 	= $tablename;
		$trash->tableid   	= $id;
		$trash->user_id   	= $_SESSION['ID'];
		$trash->deletedDate = date("Y-m-d");
		
		$res = $db->insertObject($trash, "trashcan");
		
		return $res;
	}
	
	function checkCookieLogin() {
		global $db;
		
	    $uname = $_COOKIE['uname']; 
	    if (!empty($uname)) {   
			$user = $db->selectObject("users", "login_session ='{$uname}'");

			if($user->id) {
	        	$_SESSION['COOKIE'] = $uname;	
				$_SESSION['ID'] = $user->id;
				$_SESSION['NAME'] = $user->firstname;
				$_SESSION['TYPE'] = $user->user_type;
	        	// reset expiry date
	        	setcookie("uname", $uname, time()+3600*24, '/');
				return true;
			} else {
				return false;
			}
	    }
	}
	
	function checkLoan($loan_id, $new_payment, $payment_id = 0) {
		global $db;
		
		$total_loan 	  = getLoanWithInterest($loan_id);
		
		if($payment_id == 0) { 
			$total_payment	  = getTotalPayment($loan_id) + $new_payment;
		} else {
			$total_payment	  = getTotalPayment($loan_id, $payment_id) + $new_payment;
		}
		

		if($total_loan == $total_payment) {
			$res = paidLoan($loan_id);
			return $res;
		} elseif($total_loan > $total_payment) {
			$res = paidLoan($loan_id, 0);
			return true;
		} else {
			$alert->msg  = "Your payment is greater than the balance of the client. Please try again.";
			$_SESSION['ALERT'][] = $alert;
			return false;
		}
		
	}
	
	function getCapitalAndInterest($loan_id, $amount) {
		global $db;
		
		$loan = $db->selectObject("loans", "id = {$loan_id}");
		//Calculation for the distribution of capital and interest and insert or update accordingly
		$interest_rate = 1 + ( $loan->monthsToPay * $loan->interest );
		
		$amt = new stdclass();
		$amt->capital       = $amount / $interest_rate;
		$amt->interest      = $amount - $amt->capital;
		
		return $amt;
	}
	
	function createBatchPayments($client_id, $amount = 0, $date) {
		global $db;
		
		$paymentDate   = date('Y-m-d', strtotime($date));
		
		if($amount == '' || $amount == 0) {
			$amount = 0;
			$payment = $db->selectObject("payments", "client_id = {$client_id} AND DATE_FORMAT(paymentDate, '%Y-%m-%d') ='{$paymentDate}' AND active = 1");
		
			if(!empty($payment->id)) {
				$res = $db->delete("payments", "id = {$payment->id}");
				
				
			}
			return true;	
		} else {
		
			//Get the loan to be paid of 
			$loan = $db->selectObject("loans", "client_id = {$client_id} AND paid = 0 and active = 1");
	
			//Calculation for the distribution of capital and interest and insert or update accordingly
			$interest_rate = 1 + ( $loan->monthsToPay * $loan->interest );
			$capital       = $amount / $interest_rate;
			$interest      = $amount - $capital;
		
			//Get if there is any payment for the given client
			
			
			$payment = $db->selectObject("payments", "client_id = {$client_id} AND active = 1 and DATE_FORMAT(paymentDate, '%Y-%m-%d') = '{$paymentDate}'");
			
			$loan_verified = checkLoan($loan->id, $amount, $payment->id);
		
			if($loan_verified) {
				if(!empty($payment->id)) {
					$payment->capital  = $capital;
					$payment->interest = $interest;

					$res = $db->updateObject($payment, "payments");
				
				} else {
					@$payment->capital        = $capital;
					$payment->interest       = $interest;
					$payment->loan_id        = $loan->id;
					$payment->client_id      = $client_id;
					$payment->paymentDate    = $paymentDate;
					$payment->remarks        = 'Added using batch payment.';
					$payment->created  		 = time();
					$payment->createdBy  	 = $_SESSION['ID'];
					$payment->active		 = 1;
			
					$res = $db->insertObject($payment, "payments");
				}
			} else {
				return false;
			}
		}
		
		return $res;
	}
		
	function deleteNote($id) {
		global $db;
		
		//Deactivate the note
		$note = $db->selectObject("reminders", "id = {$id}");
		$note->active = 0;
		$res = $db->updateObject($note, "reminders");
		
		//Add it to trashcan
		$res = addToTrashCan($id, "reminders");
		
		if($res) {
			$alert->type = "success";
			$alert->msg  = "You have successfully deleted the note of " . getClientName($note->client_id) . ".";
		} else {
			$alert->msg  = "Oops, something went wrong. Please try again.";
		}

		$alerts[] = $alert;
		$_SESSION['ALERT'] = $alerts;
		
		return $res;
	}
	
	function getBusinessInformation($client = array()) {
		global $db;
		
		$addressArr = explode(",", $client->businessAddress);

		$businessInfo  = "<address>";
		if($client->businessName) {
			$businessInfo  = "<strong>{$client->businessName}</strong><br />";
		}
		foreach($addressArr as $item) {
			if($item) {
				$businessInfo .= "{$item}<br />";
			}
		}
		if ($client->contact) {
			$businessInfo .= "<abbr title='Phone'>P:</abbr> {$client->contact}";
		}
		
		$businessInfo .= "</address>";
		
		return $businessInfo;
	}
	
	/**
	 * Returns the balance of the client
	 *
	 * Loop all the active loans and get their interest by getLoanWithInterest function
	 * Each loan, will get all the corresponding payment for the given loan
	 *
	 * @param int $id The client id
	 * @return double The balance of the client
	 */
	function getClientBalance($id) {
		global $db;
		
		$amount   = 0;
		$loans 	  = $db->selectObjects("loans", "paid = 0 AND client_id = {$id} AND active = 1", "loanDate DESC");
		
		foreach($loans as $item) {
			$total_loan = getLoanWithInterest($item->id);	
			$payments   = $db->selectSum("payments", "capital + interest", "loan_id = {$item->id} AND active = 1");
			$amount    += ($total_loan - $payments);
		}
		
		return $amount;
	}
	
	function getClientName($id, $middlename = false) {
		global $db;

		$client = $db->selectObject("clients", "id = {$id}");
	
		return $client->accountname;
	} 
	
	function getCollectorName($id) {
		global $db;

		$collector = $db->selectObject("users", "id = {$id}");
		return $collector->firstname . ' ' . $collector->lastname;
	}
	
	function getLastPayment($id) {
		global $db;
		
		$sql = "SELECT capital, interest, paymentDate FROM payments WHERE active = 1 AND loan_id IN (Select id FROM loans WHERE paid = 0 AND active = 1 AND client_id = {$id}) ORDER BY paymentDate DESC";
		$payment = $db->selectObjectBySql($sql);

		if(!empty($payment)) {
			return formatToAmount($payment->capital + $payment->interest) . " last " . date('M d, Y',strtotime($payment->paymentDate));
		}
	}
	
	/**
	 * Returns all the loans amount of the given client separated by comma (,)
	 *
	 * Get all the loans of a particular client
	 * 
	 *
	 * @param int $id The id of the client
	 * @return string Loans separated by comma (,)
	 */
	function getLoan($id) {
		global $db;
		
		$loans = $db->selectObjects("loans", "paid = 0 AND client_id = {$id} AND active = 1", "loanDate ASC");
		$data = array();
		
		foreach($loans as $loan) {
			$data[] = formatToAmount($loan->capital);
		}
		
		return implode(", ", $data);
	}
	
	function getLoanBalance($loan_id) {
		global $db;
		
		$loanWithInterest = getLoanWithInterest($loan_id);	
		$payments   	   = $db->selectSum("payments", "capital + interest", "loan_id = {$loan_id} AND active = 1");
		
		return $loanWithInterest - $payments;
	}
	
	/**
	 * Returns all the loans due dates of the given client separated by comma (,)
	 *
	 * Get all the loans dates of a particular client
	 * by computing the number of months adding in loanDate
	 *
	 * @param int $id The id of the client
	 * @return string Loans date dates separated by comma (,)
	 */
	function getLoanDueDate($id) {
		global $db;
		
		$loans = $db->selectObjects("loans", "paid = 0 AND client_id = {$id} AND active = 1", "loanDate ASC");
		$data = array();
		
		foreach($loans as $item) {
		
			$duration   = 30 * $item->monthsToPay;
			$date = date('M d, Y',strtotime($item->loanDate) + (24*3600*$duration));
			
			$data[] = $date;
		}
		
		return implode(", ", $data);
	}

	/**
	 * Returns all the loans types of the given client separated by comma (,)
	 *
	 * Get all the loans of a particular client
	 * 
	 *
	 * @param int $id The id of the client
	 * @return string Loans types separated by comma (,)
	 */
	function getLoanType($id) {
		global $db;
		
		$loans = $db->selectObjects("loans", "paid = 0 AND client_id = {$id} AND active = 1", "loanDate ASC");
		
		foreach($loans as $loan) {
			$data[] = $db->selectValue("categories", "category", "id = {$loan->category_id}");
		}
	
		return @implode(", ", $data);
	}
	
	/**
	 * Returns the loan with total computed interest
	 *
	 * Compute the loan with the number of months, 
	 * interest rate and capital
	 *
	 * @param int $loan_id The id of the loan to be computed
	 * @return double The loan plus the total interest
	 */
	function getLoanWithInterest($loan_id) {
		global $db;
		
		$loan = $db->selectObject("loans", "id = {$loan_id} AND active = 1");
		
		return ($loan->monthsToPay * ($loan->interest * $loan->capital) ) + $loan->capital;
	}
	
	function getNumberOfLoanCycle($id) {
		global $db;
		
		$num = $db->countObjects("loans", "client_id = {$id} AND active = 1");

		if (!in_array(($num % 100),array(11,12,13))){
			switch ($num % 10) {
			// Handle 1st, 2nd, 3rd
		        case 1:  return $num.'st';
		        case 2:  return $num.'nd';
		        case 3:  return $num.'rd';
	  		}
		}
		return $num.'th';
	}
	
	
	function getTotalPayment($loan_id, $except = 0) {
		global $db;
		
		$payments   = $db->selectSum("payments", "capital + interest", "loan_id = {$loan_id} AND id != {$except} AND active = 1");
	
		return $payments;
	}
	
	function getUserName($id) {
		global $db;

		$user = $db->selectObject("users", "id = {$id}");
	
		return $user->firstname . ' ' . $user->lastname;
	}
	
	function paidLoan($loan_id, $paid = 1) {
		global $db;
		
		$loan = $db->selectObject("loans", "id = {$loan_id} AND active = 1");
				
		$loan->paid = $paid;
		$res = $db->updateObject($loan, "loans");
			
		if($paid == 1) {
			if($res) {
				@$alert->type = "info";
				$alert->msg  = "The " . formatToAmount($loan->capital) . " loan of " . getClientName($loan->client_id) . " is now fully paid.";
				$_SESSION['ALERT'][] = $alert;
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
				$_SESSION['ALERT'][] = $alert;
			}
		}
	
		
		return $res;
	}	

?>