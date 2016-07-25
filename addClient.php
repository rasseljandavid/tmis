<?php
	include('config.php');
	
	if(isset($_POST['submit_client'])) {

		if(empty($_POST['id'])) {
			//Client Detail
			$client = new stdclass();
			$client->firstname 			= $_POST['clients']['firstname'];
			$client->accountname 		= $_POST['clients']['accountname'];
			$client->lastname 			= $_POST['clients']['lastname'];
			$client->remarks 			= $_POST['clients']['remarks'];
			$client->currentAddress 	= $_POST['clients']['currentAddress'];
			$client->contact 	      	= $_POST['clients']['contact'];
			$client->active 			= $_POST['clients']['active'];
			$client->user_id 			= $_POST['clients']['user_id'];
			$client->position 			= $db->max("clients", "position") + 1;
			$client->created 			= time();
			$client->createdBy 			= $_SESSION['ID'];
		
			$client_id = $db->insertObject($client, "clients");
		
		
			$alert = new stdclass();
			if($client_id) {
				$alert->type = "success";
				$alert->msg  = "You have successfully added " . getClientName($client_id) . ".";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}	

			$alerts[] = $alert;
			$_SESSION['ALERT'] = $alerts;
	
		
		} else {
			//Client Detail
			$client_id = (int) $_POST['id'];
			$client = $db->selectObject("clients", "id={$client_id}");
			
			
			foreach($_POST['clients'] as $key => $value) {
				$client->$key = $value; 
			}

			$res = $db->updateObject($client, "clients");
			
			$alert = new stdclass();
			if($res) {
				$alert->type = "success";
				$alert->msg  = "You have successfully updated " . getClientName($client_id) . " info.";
			} else {
				$alert->msg  = "Oops, something went wrong. Please try again.";
			}	
			$alerts[] = $alert;
			$_SESSION['ALERT'] = $alerts;

		}
		
		//Redirect to the recently added client page
		header("location: client.php?id={$client_id}");
		exit();
		
	}
	
	
	
	$pageheader = "New Account";
	
	if(!empty($_GET['id'])) {
		$id = (int)$_GET['id'];
		
		$client 			= $db->selectObject("clients", "id = {$id}");
		
		$pageheader = "Edit Account";
	}
	
	$martialStatus = getMartialStatus();
	$collectors    = $db->selectObjects("users", "user_type = 2 AND active = 1");

	include('header.php');
?>	

	<div class="row-fluid">

		<form action="addClient.php" method="post">
			<input type="hidden" name="id" value="<?php echo $client->id; ?>" />
			<input type="hidden" name="clients[active]" value="1" />
		<fieldset>
			<legend>Personal Details</legend>
			<input type="text" placeholder="*&nbsp;&nbsp;Account name" name="clients[accountname]" class="input-block-level"  value="<?php echo @$client->accountname; ?>" required />
			
			<input type="text" placeholder="&nbsp;&nbsp;First name" name="clients[firstname]" class="input-block-level" value="<?php echo @$client->firstname; ?>" />
			<input type="text" placeholder="&nbsp;&nbsp;Last name" name="clients[lastname]" class="input-block-level"  value="<?php echo @$client->lastname; ?>" />
			<input type="text" placeholder="&nbsp;&nbsp;Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Lot & blk no. street barangay, city, zipcode)" name="clients[currentAddress]" class="input-block-level" value="<?php echo @$client->currentAddress; ?>" />
			<input type="text" placeholder="&nbsp;&nbsp;Contact" name="clients[contact]" class="input-block-level" value="<?php echo @$client->contact; ?>" />
		</fieldset>
	    
	
		<fieldset>
			<legend>Other Details</legend>
		
			<select name="clients[user_id]" id="user_id" class="input-block-level select_placeholder">
				<option value="" selected>&nbsp;&nbsp;Salesman/Agent</option>
				<?php foreach($collectors as $item) : ?>
					<option value='<?php echo $item->id; ?>' <?php if($item->id == $client->user_id) echo "selected"; ?>><?php echo $item->firstname . ' ' . $item->lastname; ?></option>
				<?php endforeach; ?>
			</select>
			<textarea name="clients[remarks]" placeholder="&nbsp;&nbsp;Client Remarks" id="client_remarks" cols="30" rows="10" class="input-block-level"><?php echo $client->remarks; ?></textarea>

		</fieldset>
		
		
		<button class="btn btn-primary" name="submit_client">Save Client</button>
		</form>
	
	</div>

	<script type="text/javascript">
	
	$(function() {
		//Counters for the comaker and reference dynamic field
	
		
		//Make the select placeholder to be look like other placeholder color
		$(".select_placeholder").on("change", function () {
			if($(this).val() == "") $(this).addClass("empty");
			else $(this).removeClass("empty")
		});
		$(".select_placeholder").change();
		
		
	});
	</script>
	
	<?php include('footer.php'); ?>