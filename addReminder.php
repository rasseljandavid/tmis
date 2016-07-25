<?php

	include('config.php');
	
	if(isset($_POST['submit_reminder'])) {
		
		$reminder = new stdClass();
		$reminder->reminder         = $_POST['reminder'];
		$reminder->active           = 1;
		$reminder->dateReminder     = date("Y-m-d");
		$reminder->client_id        = $_POST['client_id'];
		$reminder->user_id          = $_SESSION['ID'];
		
		$res = $db->insertObject($reminder,"reminders");
	
		if($res) {
			$alert->type = "success";
			$alert->msg  = "You have successfully added a new note for " . getClientName($reminder->client_id) . ".";
		} else {
			$alert->msg  = "Oops, something went wrong. Please try again.";
		}
		
		$alerts[] = $alert;
		$_SESSION['ALERT'] = $alerts;
	
		header("location: client.php?id={$reminder->client_id}");
		exit();
	}
?>