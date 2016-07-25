<?php
	//Connect to DB
	include('config.php');
	
	//Get the structure of the accordion
	$latest_date   = $db->max("loans","loanDate");
	
	$earliest_date = $db->min("loans","loanDate");
	$accordions_data = showAccordion($latest_date, $earliest_date);
	
	//If user first time to go to the payment page set the sessions for current date and month
	if(!isset($_SESSION['LOAN_MONTH']) && !isset($_SESSION['LOAN_YEAR'])) {
		$_SESSION['LOAN_MONTH'] = date("F");
		$_SESSION['LOAN_YEAR']  = date("Y", strtotime($latest_date));
	} 
	

	//Set the new current month and year if the user select in the accordion
	if(isset($_GET['month']) && isset($_GET['year'])) {
		$_SESSION['LOAN_MONTH'] = $_GET['month'];
		$_SESSION['LOAN_YEAR'] = $_GET['year'];
	}

	//Paginate the records
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 15;
	$startpoint = ($page * $limit) - $limit;

	$statement = "clients, loans WHERE DATE_FORMAT(loanDate, '%M %Y') = '{$_SESSION['LOAN_MONTH']} {$_SESSION['LOAN_YEAR']}' AND clients.id = client_id AND loans.active = 1 ORDER BY loanDate desc, loans.id desc";

	$sql = "SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}";
	$loans = $db->selectObjectsBySql($sql);
	
	//Total loans
	$total_year  = $db->selectSum("loans", "capital", "active = 1 AND DATE_FORMAT(loanDate, '%Y') = '{$_SESSION['LOAN_YEAR']}'");
	$total_month = $db->selectSum("loans", "capital", "active = 1 AND DATE_FORMAT(loanDate, '%M %Y') = '{$_SESSION['LOAN_MONTH']} {$_SESSION['LOAN_YEAR']}'");

	$pageheader = "Loans";
	
	//Include the header and the navigation
	include('header.php');
?>

	<div class="row-fluid">
		<div class="span3">
			
			<ul class="unstyled sidebar"> 
				<li class="nav-header">Total Loans</li> 
				<li><?php echo $_SESSION['LOAN_MONTH']; ?>: <?php echo formatToAmount($total_month); ?></li>
				<li><?php echo $_SESSION['LOAN_YEAR']; ?>: <?php echo formatToAmount($total_year); ?></li>
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
				
					<div id="collapse<?php echo $item->year; ?>" class="accordion-body collapse <?php if($item->year == $_SESSION['LOAN_YEAR']) echo "in"; ?>">
						<div class="accordion-inner">
							<ul class="nav nav-tabs nav-stacked">
								<?php foreach($item->months as $month) : ?>
								<li <?php if($month == $_SESSION['LOAN_MONTH'] && $item->year == $_SESSION['LOAN_YEAR']) echo "class='active'"; ?>><a href="loans.php?month=<?php echo $month; ?>&year=<?php echo $item->year; ?>"><?php echo $month; ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
			 	</div>
			<?php endforeach; ?>
			</div>
			
		
		</div>
		
		<div class="span9">
			<table class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th>Date</th>
						<th>Name</th>
						<th>Loan</th>
						<th>Rate</th>
						<th>Months</th>
						<th>Category</th>
						<th>Encoded By</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($loans as $loan) : ?>
			
					<tr>
							<td><?php echo date('M d, Y', strtotime($loan->loanDate)); ?></td>
							<td><a href="client.php?id=<?php echo $loan->client_id; ?>#loans"><?php echo $loan->firstname; ?> <?php echo $loan->lastname; ?></a></td>
						
							<td><?php echo formatToAmount($loan->capital); ?></td>
							<td><?php echo formatToPercent($loan->interest); ?></td>
							<td><?php echo $loan->monthsToPay; ?></td>
							<td><?php echo $db->selectValue("categories", "category", "id ={$loan->category_id}"); ?></td>
						
							<td><?php echo $db->selectValue("users", "firstname", "id ={$loan->createdBy}"); ?> <?php echo $db->selectValue("users", "lastname", "id ={$loan->createdBy}"); ?></td>
					</tr>
						
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php echo paginate($statement,$limit,$page); ?> 
		</div>
	</div>
	<!-- Include the footer -->
	<?php include('footer.php'); ?>