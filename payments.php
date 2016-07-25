<?php
	//Connect to DB
	include('config.php');
	//Get the structure of the accordion
	$latest_date   = $db->max("payments","paymentDate");
	$earliest_date = $db->min("payments","paymentDate");
	$accordions_data = showAccordion($latest_date, $earliest_date);
	
	//If user first time to go to the payment page set the sessions for current date and month
	if(!isset($_SESSION['PAYMENT_MONTH']) && !isset($_SESSION['PAYMENT_YEAR'])) {
		$_SESSION['PAYMENT_MONTH'] = date("F");
		$_SESSION['PAYMENT_YEAR']  = date("Y", strtotime($latest_date));
	} 
	
	//Set the new current month and year if the user select in the accordion
	if(isset($_GET['month']) && isset($_GET['year'])) {
		$_SESSION['PAYMENT_MONTH'] = $_GET['month'];
		$_SESSION['PAYMENT_YEAR'] = $_GET['year'];
	}

	//Paginate the records
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 15;
	$startpoint = ($page * $limit) - $limit;
	$statement = "clients, payments WHERE DATE_FORMAT(paymentDate, '%M %Y') = '{$_SESSION['PAYMENT_MONTH']} {$_SESSION['PAYMENT_YEAR']}' AND clients.id = client_id AND payments.active = 1 ORDER BY paymentDate desc, payments.id desc";
	
	//Get all the info for the payment of clients
	$sql = "SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}";
	$payments = $db->selectObjectsBySql($sql);

	
	//Mold the records
	for($i = 0; $i < count($payments); $i++) {
		$payments[$i]->paymentDate = date('M d, Y', strtotime($payments[$i]->paymentDate));
		$payments[$i]->name        = "<a href='client.php?id={$payments[$i]->client_id}#payments'>{$payments[$i]->accountname}</a>";
		$payments[$i]->amount      = formatToAmount($payments[$i]->capital + $payments[$i]->interest);
		$loan		 			   = $db->selectObject("loans", "id = {$payments[$i]->loan_id} AND active = 1");
		$payments[$i]->encodedBy   = $db->selectValue("users", "firstname", "id ={$payments[$i]->createdBy}") . ' ' . $db->selectValue("users", "lastname", "id ={$payments[$i]->createdBy}");
	}
	
	//Total loans
	$total_year  = $db->selectSum("payments", "capital + interest", "active = 1 AND DATE_FORMAT(paymentDate, '%Y') = '{$_SESSION['PAYMENT_YEAR']}'");
	$total_month = $db->selectSum("payments", "capital + interest", "active = 1 AND DATE_FORMAT(paymentDate, '%M %Y') = '{$_SESSION['PAYMENT_MONTH']} {$_SESSION['PAYMENT_YEAR']}'");	

	$pageheader = "Collections";

	include('header.php');
?>

	<div class="row-fluid">
		<div class="span3">
			
			<ul class="unstyled sidebar"> 
				<li class="nav-header">Total Collections</li> 
				<li>As of <?php echo $_SESSION['PAYMENT_MONTH']; ?>: <?php echo formatToAmount($total_month); ?></li>
				<li>As of <?php echo $_SESSION['PAYMENT_YEAR']; ?>: <?php echo formatToAmount($total_year); ?></li>
			</ul>

			<hr />
			
			<div class="accordion" id="accordion2">
		 	<?php foreach($accordions_data as $item) : ?>
				<div class="accordion-group">
					<div class="accordion-heading">
						<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $item->year; ?>">
					    	<?php echo $item->year; ?>
					 	</a>
					</div>
				
					<div id="collapse<?php echo $item->year; ?>" class="accordion-body collapse <?php if($item->year == $_SESSION['PAYMENT_YEAR']) echo "in"; ?>">
						<div class="accordion-inner">
							<ul class="nav nav-tabs nav-stacked">
								<?php foreach($item->months as $month) : ?>
								<li <?php if($month == $_SESSION['PAYMENT_MONTH'] && $item->year == $_SESSION['PAYMENT_YEAR']) echo "class='active'"; ?>><a href="payments.php?month=<?php echo $month; ?>&year=<?php echo $item->year; ?>"><?php echo $month; ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
			 	</div>
			<?php endforeach; ?>
			</div>	
		</div>
			
		<div class="span9">			
				<table class="table table-bordered table-hover ">
					<thead>
						<tr>
							<th>Date</th>
							<th>Name</th>
							<th>Amount</th>
							<th>Encoded By</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($payments as $item): ?>
						<tr>
							<td><?php echo $item->paymentDate; ?></td>
							<td><?php echo $item->name; ?></td>
							<td><?php echo $item->amount; ?></td>
							<td><?php echo $item->encodedBy; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
		 		<?php echo paginate($statement,$limit,$page); ?> 
		</div>
	</div>
	<!-- Include the footer -->
	<?php include('footer.php'); ?>