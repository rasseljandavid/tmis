<?php
	include('config.php');
	
	//Get the client information with image	
	$id = (int)$_GET['id'];
	$client = $db->selectObject("clients","id = {$id}");
	@$client->image = $configs['client_image_directory'] . $db->selectValue("images", "filename", "client_id = {$client->id}");

	if(!file_exists($client->image) || is_dir($client->image)) {
		$client->image = "images/default.png";
	}
	
	$client->references = $db->selectObjects("references", "type='reference' AND client_id = {$client->id}");
	$client->comaker    = $db->selectObjects("references", "type='comaker' AND client_id = {$client->id}");
	

	//Get the loans
	$loans         		    = $db->selectObjects("loans", "client_id = {$id} AND active = 1", "loanDate desc");
	
	//Get the payments
	$firstPaymentDate       = $db->selectColumn("payments", "paymentDate", "client_id = {$id} AND active = 1", "paymentDate asc");
	if($firstPaymentDate[0]) {
		$startDate = date("Y-m-d", strtotime($firstPaymentDate[0]));
	} else {
		$startDate = $db->selectValue("loans", "loanDate", "client_id = {$id} AND active = 1 AND paid = 0");
	}
	
	$endDate   = date("Y-m-d");
	
	$payment_date = array();
	while(strtotime($endDate) >= strtotime($startDate)) {
		$payment_date[] = date("F d, Y", strtotime($startDate));
		$parts = explode('-',$startDate); 
		@$startDate = date('Y-m-d',mktime(1,1,1,$parts[1],($parts[2]+1),$parts[0])); 	
	}

	$payment_date = array_reverse($payment_date);

	for($i = 0; $i < count($payment_date); $i++) {
		$payments[$i] = $db->selectObject("payments", "DATE_FORMAT(paymentDate, '%M %d, %Y') = '{$payment_date[$i]}' AND client_id = {$id} AND active = 1");
		@$payments[$i]->paymentDate = $payment_date[$i];
		if(strftime("%A",strtotime($payment_date[$i])) == "Sunday") {
			$payments[$i]->type = "sunday"; 
		} else {
			if(empty($payments[$i]->id)) {
				$payments[$i]->type = "pass"; 
			} else {
				$payments[$i]->type = "good"; 
			}
		}

	}

	//Get the reminders and count them
	$reminders             = $db->selectObjects("reminders", "client_id = {$id} AND active = 1", "dateReminder desc");
	$reminders['count']    = count($reminders);
	
	//Get the total loans
	$total_loans     	   = $db->selectSum("loans", "(capital * interest * monthsToPay) + capital", "client_id = {$id} AND active = 1");

	//Get the total payments
	$total_payments  	   = $db->selectSum("payments", "(interest + capital)", "client_id = {$id} AND active = 1");

	//Compute the remaining balance
	$remaining_balance     = $total_loans - $total_payments;
	
	$pageheader = "Client Details";
	
	include('header.php');
?>	

	<div class="row-fluid">
		<div class="span3">
			<p style="border-bottom: 1px solid #dfdfdf"><a href="addClient.php?id=<?php echo $client->id; ?>" style="color: #b94a48">Edit Client</a></p>
			<ul class="nav nav-tabs nav-stacked">
		
				<li><a data-toggle="modal" href="forms/payment.php?client_id=<?php echo $client->id; ?>" data-target="#newPayment">Add Collection</a></li>
				
				<li><a href="#addReminder" data-toggle="modal">Add Notes</a></li>
			</ul>
	
		</div>
		
		<div class="span9">
			
			<ul class="nav nav-tabs" style="margin-bottom: 0;">
			  <li><a href="#information" data-toggle="tab">Personal Info</a></li>
		
			  <li><a href="#payments" data-toggle="tab">Collections</a></li>

			</ul>
	
			<div class="tab-content">
			  	<div class="tab-pane" id="information">
					<div class="content">	
						<table class="personal-image-table">
							<tbody>
								<tr>
									<td>
										<img src="<?php echo $client->image; ?>" style="width: 100px; height: 100px;" class="img-rounded " />
									</td>
									<td>
										<h4 style="margin-left: 15px;"> <?php echo getClientName($client->id, true); ?></h4>
											
									</td>
								</tr>
							</tbody>
						</table>
						
						<table class="table info">
						
							<tbody>
								<!--
								<tr>
									<td>Birthday:</td>
									<td><?php echo date("F d, Y", strtotime($client->birthdate)); ?></td>
								</tr>
								
								<tr>
									<td>Martial Status:</td>
									<td><?php echo $client->martialStatus; ?></td>
								</tr>
								
								<tr>
									<td>Spouse:</td>
									<td><?php echo $client->spouse; ?></td>
								</tr>
							
								<tr>
									<td>Permanent Address:</td>
									<td><?php echo $client->permanentAddress; ?></td>
								</tr>
									-->
								<tr>
									<td>Current Address:</td>
									<td><?php echo $client->currentAddress; ?></td>
								</tr>

								<tr>
									<td>Contact:</td>
									<td><?php echo $client->contact; ?></td>
								</tr>
						
								<!--
								<tr>
									<td>Valid ID:</td>
									<td><?php echo $client->validID; ?></td>
								</tr>
								
								<tr>
									<td>valid ID No:</td>
									<td><?php echo $client->validIDNo; ?></td>
								</tr>
								
								<tr>
									<td>Business Type:</td>
									<td><?php echo $client->businessType; ?></td>
								</tr>
								
								<tr>
									<td>Business Name:</td>
									<td><?php echo $client->businessName; ?></td>
								</tr>
								
								<tr>
									<td>Business Address:</td>
									<td><?php echo $client->businessAddress; ?></td>
								</tr>

								<tr>
									<td>Years Operated:</td>
									<td><?php echo $client->yearsOperated; ?></td>
								</tr>
								
								<tr>
									<td>Daily Sales:</td>
									<td><?php echo formatToAmount($client->dailySales); ?></td>
								</tr>
									
								<tr>
									<td>Daily Expenses:</td>
									<td><?php echo formatToAmount($client->dailyExpenses); ?></td>
								</tr>
								<?php foreach($client->references as $item) :?>
								<tr>
									<td>Reference:</td>
									<td>
										<?php echo $item->name . "<br />"; ?>
										<?php echo $item->address . "<br />"; ?>
										<?php echo $item->contact; ?>
									</td>
								</tr>
								<?php endforeach; ?>
							
								<tr>
									<td>Application Date:</td>
									<td><?php echo date("F d, Y", strtotime($client->applicationDate)); ?></td>
								</tr>

								<?php foreach($client->comaker as $item) :?>
								<tr>
									<td>Comaker:</td>
									<td>
										<?php echo $item->name . "<br />"; ?>
										<?php echo $item->address . "<br />"; ?>
										<?php echo $item->contact; ?>
									</td>
								</tr>
								<?php endforeach; ?>
								
								<tr>
									<td>Collateral:</td>
									<td><?php echo $client->collateral; ?></td>
								</tr>
								-->
								<tr>
									<td>Remarks:</td>
									<td><?php echo $client->remarks; ?></td>
								</tr>
							</tbody>
						</table>
						
						
						<?php if($reminders['count'] > 0) : ?>
						<h4>Notes about <?php echo $client->firstname; ?></h4>
						<?php foreach($reminders as $item) : ?>
						<?php if($item->id) : ?>
						<div class="note">
							<div class="note-action">
								<a href="delete.php?id=<?php echo $item->id; ?>&type=notes" class="icon-trash"  onclick="return confirm('Are you sure you want to delete this note?');"></a>
							</div>
							<div class="note-header">
								<p><span class="icon-comment"></span><strong><?php echo date('l, F d', strtotime($item->dateReminder)); ?></strong></p>
								<p class="muted">Note by <?php echo getUserName($item->user_id); ?></p>
							</div>
							
							<div class="note-body">
								<p><?php echo $item->reminder; ?></p>
							</div>
						</div>
						<?php endif; ?>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			  
			  	<div class="tab-pane" id="payments">
				<div class="content">
				<h4 class="tab-header">Collections of <?php echo $client->accountname; ?></h4>
					<table class="table table-bordered table-hover payments">
							<thead>
							<tr>
								<th>Date</th>
								<th>Capital</th>
								<th>Interest</th>
								<th>Total</th>
								<th class="actions"></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($payments as $item) : ?>
							<tr class="<?php if($item->type =="sunday") echo "warning"; elseif($item->type=="pass") echo "error"; ?>">
								<td><?php echo date('M d, Y', strtotime($item->paymentDate)); ?></td>
								<td><?php echo formatToAmount($item->capital, " ", 4); ?></td>
								<td><?php echo formatToAmount($item->interest, " ", 4); ?></td>
								<td><?php echo formatToAmount(($item->capital + $item->interest), " "); ?></td>
								
								<td class="actions">
									<?php if($item->type != "sunday") : ?>
									<a href="forms/payment.php?payment_id=<?php echo $item->id; ?>" data-target="#newPayment">Edit</a>  
									<a href="delete.php?id=<?php echo $item->id; ?>&type=payment" class="icon-trash " onclick="return confirm('Are you sure you want delete this payment?');"></a>
									<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			
				</div>
			</div>
	
			
		</div>
	</div>
	
	<div id="addReminder" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form action="addReminder.php" method="post">
	  		<div class="modal-header">
	    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	    		<h3 id="myModalLabel">New Notes for <?php echo getClientName($client->id); ?></h3>
	  		</div>
	  		<div class="modal-body">
	   		
					<input type="hidden" name="client_id" value="<?php echo $client->id; ?>" />
				
					<textarea placeholder="Add Notes Here..." name="reminder" rows="10" required class="input-block-level"></textarea>

		
	  		</div>
	  		<div class="modal-footer">
	    		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
	    		<button class="btn btn-primary" name="submit_reminder">Save Reminder</button>
	  		</div>
		</form>
	</div>

	<script>
		$(function() {
			var hash = window.location.hash.substring(1); //Puts hash in variable, and removes the # character
			if(hash == "") {
				hash = "information";
			}
	
			$('ul.nav-tabs a[href="#' + hash +'"]').tab('show');
			
						$("a[data-target=#newPayment]").click(function(ev) {
						    ev.preventDefault();
						    var target = $(this).attr("href");
						
						    // load the url and show modal on success
						    $("#newPayment .modal-body").load(target, function() { 
						         $("#newPayment").modal("show"); 
						    });
						});
		});
		
	</script>
	<?php include('footer.php'); ?>