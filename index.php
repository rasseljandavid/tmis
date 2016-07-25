<?php
	//Connect to DB
	include('config.php');
	//Side bar info
	$total_payments   = $db->selectSum("payments", "capital + interest", "active = 1");
	$total_clients    = $db->countObjects("clients", null);

	//Main content
	$payments = $db->selectObjectsBySql("SELECT DATE_FORMAT(paymentDate, '%M_%d_%Y') dateCollection, sum(capital + interest) totalCollection FROM payments WHERE active = 1 AND client_id <> 0 GROUP BY DATE_FORMAT(paymentDate, '%M-%D-%Y') ORDER BY paymentDate DESC");
	$loans 	  = $db->selectObjectsBySql("SELECT DATE_FORMAT(loanDate, '%M_%d_%Y') dateLoan, sum(capital) totalLoan FROM loans WHERE active = 1 GROUP BY DATE_FORMAT(loanDate, '%M-%D-%Y') ORDER BY loanDate DESC");
	//Build the loans and payments array
	$loansFormatted 	= array();
	$paymentsFormatted  = array();
	//echo "<pre>";print_r($payments);exit();
	for($i = 0; $i < count($loans); $i++) {
		$loansFormatted[$loans[$i]->dateLoan] = $loans[$i]->totalLoan;
	}
	
	for($i = 0; $i < count($payments); $i++) {
		$paymentsFormatted[$payments[$i]->dateCollection] = $payments[$i]->totalCollection;
	}
	
	//Get the oldest and latest loan date
	$objOldestDate = $db->selectObjectBySql("SELECT min(loanDate) minDate FROM loans WHERE active = 1");
	$oldestLoanDate = date("F d Y", strtotime($objOldestDate->minDate));
	$today			= date("F d Y");

	//Setup the listing
	$data = array();
	$i = 0;
	
	while(strtotime($today) >= strtotime($oldestLoanDate)) {
		//These will hold and show the collection of the day
		$tempPayment = '';
		$tempLoan    = '';
		$key = str_replace(" ", "_", $today);

		if(!empty($loansFormatted[$key])) {
			$tempLoan	 = $loansFormatted[$key];
		}

		if(!empty($paymentsFormatted[$key])) {
			$tempPayment = $paymentsFormatted[$key];
		}
							
					
		if($tempPayment || $tempLoan)  {
			@$data[$i]->date    = date("l, F d ", strtotime($today));
			@$data[$i]->loan    = formatToAmount($tempLoan);
			@$data[$i]->payment = formatToAmount($tempPayment);
			$i++;
		}
					
		//Decrement the date
		$today = date("F d Y", strtotime($today) - 86400);
		
		//Limit only to 20, who need more than that anyway?
		if($i > 19) {
			break;
		}
	}

	//Set the title page
	$pageheader = "Dashboard";
	
	//Include the header and the navigation
	include('header.php');
?>

	<div class="row-fluid">
		<div class="span3">
			
			 <ul class="unstyled sidebar"> 
				<li class="nav-header">Total Performance</li> 
				<li>Collections: <?php echo formatToAmount($total_payments); ?></li>
			
			</ul>

		</div>
		
		<div class="span9">

			<table class="table table-hover table-striped ">
				<thead>
					<tr>
						<th>Date</th>
						<th>Collection</th>
					</tr>
				</thead>
				<tbody>
		
				<?php foreach($data as $item) : ?>
					<tr>
						<td><?php echo $item->date; ?></td>
						<td><a data-toggle="modal" href="forms/paymentSummary.php?date=<?php echo date("F-d-Y", strtotime($item->date)); ?>" data-target="#paymentSummary"><?php echo $item->payment; ?></a></td>
					</tr>
				<?php endforeach ?>
				</tbody>	
			</table>     
		</div>
	</div>
	<!-- Include the footer -->

	<script type="text/javascript">
	
	$("a[data-target=#loanSummary]").click(function(ev) {
	    ev.preventDefault();
	    var target = $(this).attr("href");
	
	    // load the url and show modal on success
	    $("#loanSummary .modal-body").load(target, function(response, status, xhr) { 
	       $("#loanSummary").modal("show"); 
	    });
	});
	
	$("a[data-target=#paymentSummary]").click(function(ev) {
	    ev.preventDefault();
	    var target = $(this).attr("href");
		
	    // load the url and show modal on success
	    $("#paymentSummary .modal-body").load(target, function() { 
	    	$("#paymentSummary").modal("show"); 
	    });
	});
	
	</script>
	
	<?php include('footer.php'); ?>