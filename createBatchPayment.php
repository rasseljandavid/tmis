<?php
     
	//Connect to DB
	include('config.php');
	
	if(isset($_POST['submit'])) {
		
		//Save the payments
		$total = 0;
		$error = 0;
		foreach($_POST['payments'] as $key => $item) {
		
			$res = createBatchPayments($key, $item, $_POST['paymentDate']);
			
			if($res) {
				$total = $total + $item;
			} else {
				$error = 1;
			}
			
			
		}
		
		$alert = new stdclass();
		if($error == 0) {
			$alert->type = "success";
			$alert->msg  = "You have successfully added " . formatToAmount($total) . " payment.";
		} else {
			$alert->msg  = "Oops, something went wrong. Please try again.";
		}
	
	
		$_SESSION['ALERT'][] = $alert;

		header('location: payments.php');
		exit();
	}
	
	if(!isset($_GET['id'])) {
		$_GET['id'] = 8; //hardcoded for now
	}
	
	$statement = "clients, users WHERE users.id = user_id AND user_id = {$_GET['id']} AND clients.active = 1  ORDER BY position";
	
	$sql 	 	 = "SELECT clients.id as client_id, CONCAT(clients.firstname, ' ', clients.lastname) AS name FROM {$statement}";
	$clients_all = $db->selectObjectsBySql($sql);
	$clients = array();
	for($i = 0; $i < count($clients_all); $i++) {
		$balance = getClientBalance($clients_all[$i]->client_id);
		if($balance > 0 ) {
			$clients[] = $clients_all[$i];
		}
	}
	//Get the collectors
	$collectors = $db->selectObjects("users","user_type='2' AND active = 1", "lastname");

	$total = '';
	$pageheader = "Batch Payment";
	
	include('header.php');
?>

	<div class="row-fluid">
		<div class="span3">
			<ul class="nav nav-tabs nav-stacked">
				<?php foreach($collectors as $item) : ?>			
				<li class="<?php if($item->id == $_GET['id']) echo "active"; ?>"><a href="createBatchPayment.php?id=<?php echo $item->id; ?>"><?php echo $item->firstname;?> <?php echo $item->lastname;?></a></li>			
				<?php endforeach; ?>
			</ul>
		</div>
			
		<div class="span9">
				<form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" id="myform">
				<input type="hidden" name="totalpaymentHidden" id="totalpaymentHidden" value="0" />
				<input type="hidden" name="collector" id="collector" value="<?php echo $_GET['id']; ?>" />
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>
								<input placeholder="Select Date Here..." class="textinput" type="text" name="paymentDate" id="paymentDate" autocomplete="off" required  />
								<a href="sortClients.php?id=<?php echo $_GET['id']; ?>" style="display: block;">Sort Client</a>
							</th>
							<th></th>
							<th>Total: <span id="totalpayment"><?php echo formatToAmount(0); ?></span></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php for($i=0; $i < count($clients); $i = $i + 2) : ?>
						<tr>
							<td><?php echo $i+1 . '. ' . $clients[$i]->name; ?>: </td>
							<td><input class="input-mini number" type="text" name="payments[<?php echo $clients[$i]->client_id; ?>]" id="id_<?php echo $clients[$i]->client_id; ?>" /></td>
							<?php if(isset($clients[$i + 1]->name)) : ?>
							<td><?php echo $i+2 . '. ' . $clients[$i + 1]->name; ?>: </td>
							<td><input class="input-mini number" type="text" name="payments[<?php echo $clients[$i+1]->client_id; ?>]" id="id_<?php echo $clients[$i+1]->client_id; ?>" /></td>
							<?php endif; ?>
						</tr>
					<?php endfor; ?>
					</tbody>	
					
					<tfooter>
						<td colspan="4"><input type="submit" name="submit" value="Save Payment" class="btn btn-primary" /></td>
					</tfooter>	
				</table>
				</form>
			</div>
		</div>
	</div>
	<!-- Include the footer -->
	<?php include('footer.php'); ?>
	<script>
		$(function() {
			$( "#paymentDate" ).datetimepicker({
				onSelect: function(dateText, inst) { 
					$('#myform .input-mini').val('');
					$("#totalpaymentHidden").val('');
					var request = $.ajax({
					  url: "lib/ajax/ajaxpopulatevalue.php",
					  type: "POST",
					  data: {dateSelected : dateText, user_id : $("#collector").val() }
					});

					request.done(function(msg) {
					    if( msg != '' ) {
					  		var obj = $.parseJSON(msg);
							
							// get all the inputs into an array.
						    var $inputs = $('#myform .input-mini');

						    $inputs.each(function() {
								var val = $(this).attr('id');
								var myinput = $(this);
				
								$.each(obj, function(key, element) {
								
									if(val == key) {
										myinput.val(element);
									}
								
								});
						       
						    });
							calculateSum();
						
						}
                    });
					request.fail(function(jqXHR, textStatus) {
					  alert( "Request failed: Reload the page and try again");
					});
				}
				
			});
			calculateSum();
		
			
			$("#myform .input-mini").each(function() {

				$(this).keyup(function(){
			   		calculateSum();
			    });
			});
			
			function calculateSum() {

				var sum = 0;
			    //iterate through each textboxes and add the values
			    $("#myform .input-mini").each(function() {
					//add only if the value is number
			     	if(!isNaN(this.value) && this.value.length!=0) {
			      		sum += parseFloat(this.value);
			      	}

			   	});
		
				$("#totalpayment").html("P " + sum.toFixed(2));
				$("#totalpaymentHidden").val(sum.toFixed(2));
			}
			
		});
	</script>