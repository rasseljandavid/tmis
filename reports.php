<?php
	include('config.php');
	

	
	$pageheader = "Reports";
	if($_POST['submit']) {
		$_POST['from'] = date("Y-m-d", strtotime($_POST['from']));
		$_POST['to']   = date("Y-m-d", strtotime($_POST['to']));
		$totalcollection = $db->selectSum("payments","capital","paymentDate BETWEEN '{$_POST['from']}' AND '{$_POST['to']}'");
		//HARDCODED id of el is 5
		$collectionEL = $db->selectObjectBySql("SELECT SUM(capital) total FROM payments, clients WHERE payments.active =1 AND clients.id = client_id AND user_id = 5 AND paymentDate BETWEEN '{$_POST['from']}' AND '{$_POST['to']}'");
		//eDebug($collectionEL);
		//HARDCODED id of Norman is 6
		$collectionNorman = $db->selectObjectBySql("SELECT SUM(capital) total FROM payments, clients WHERE payments.active =1 AND clients.id = client_id AND user_id = 6 AND paymentDate BETWEEN '{$_POST['from']}' AND '{$_POST['to']}'");
		
		//HARDCODED id of Jason is 4
		$collectionJason = $db->selectObjectBySql("SELECT SUM(capital) total FROM payments, clients WHERE payments.active =1 AND clients.id = client_id AND user_id = 4 AND paymentDate BETWEEN '{$_POST['from']}' AND '{$_POST['to']}'");
		
		//HARDCODED id of warehouse 8
		$collectionWarehouse = $db->selectObjectBySql("SELECT SUM(capital) total FROM payments, clients WHERE payments.active =1 AND clients.id = client_id AND user_id = 8 AND paymentDate BETWEEN '{$_POST['from']}' AND '{$_POST['to']}'");	
		$total = $collectionEL->total + $collectionNorman->total + $collectionJason->total + $collectionWarehouse->total;
		
	}
	include('header.php');
?>	

	<div class="row-fluid">
		<div class="span3">
			<div>
				<form action="reports.php" method="post">
					<input type="text" placeholder="From" name="from" class="input-block-level from" autocomplete="off"  value="<?php echo @$payment->from; ?>" required />
					<input type="text" placeholder="To" name="to" class="input-block-level to" autocomplete="off"  value="<?php echo @$payment->to; ?>" required />
					<input type="submit" class="btn btn-primary" name="submit" value="Create Report" />
				</form>
			</div>
	
		</div>
		
		<div class="span9">
			<?php if(!empty($total)) : ?>
			<h4>Total Collection from <?php echo $_POST['from']; ?> to <?php echo $_POST['to']; ?></h4>
			<table class="table table-bordered table-hover payments">
				<tbody>
				<tr>
					<td><strong>EL Collection</strong>:</td>
					<td><?php echo formatToAmount($collectionEL->total);?></td>
				</tr>
				<tr>
					<td><strong>Norman Collection</strong>:</td>
					<td><?php echo formatToAmount($collectionNorman->total);?></td>
				</tr>
				<tr>
					<td><strong>Jason Collection</strong>:</td>
					<td><?php echo formatToAmount($collectionJason->total);?></td>
				</tr>
				<tr>
					<td><strong>Warehouse Collection</strong>:</td>
					<td><?php echo formatToAmount($collectionWarehouse->total);?></td>
				</tr>
				
				
				<tr>
					<td><strong>Total</strong></td>
					<td><?php echo formatToAmount($total);?></td>
				</tr>
				</tbody>
				
			</table>
			<?php endif; ?>
		</div>
	</div>
	<script>
		$(function() {
			$( ".from" ).datetimepicker();
			$( ".to" ).datetimepicker();
		});
		
	</script>
	<?php include('footer.php'); ?>