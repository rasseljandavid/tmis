<?php
   	include('config.php');

	//to make pagination
	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = 150;
	$url = "?";
	$startpoint = ($page * $limit) - $limit;
	
	if($_GET['collector']) {
		$condition = " AND user_id = {$_GET['collector']} ";
		$url = "?collector={$_GET['collector']}&";
	}
	
	//Starts the condition here
	
	if($_GET['client']) {
		$condition .= " AND lastname like '{$_GET['client']}%'";
		$url = "?client={$_GET['client']}&";
	}
	
	$statement = "clients WHERE active = 1 {$condition} ORDER BY position DESC";

	$sql = "SELECT * FROM {$statement} LIMIT {$startpoint} , {$limit}";
	$clients = $db->selectObjectsBySql($sql);
	
	//Mold the records
	for($i = 0; $i < count($clients); $i++) {
		
		$clients[$i]->name      = "<a href='client.php?id={$clients[$i]->id}'>" . getClientName($clients[$i]->id). "</a>";	
		$clients[$i]->image		= "<a href='client.php?id={$clients[$i]->id}'>";
		$filename               = $configs['client_image_directory'] . $db->selectValue("images", "filename", "client_id = {$clients[$i]->id}");
		if(!file_exists($filename) || is_dir($filename)) {
			$filename = "images/default.png";
		}
	
		$clients[$i]->image	   .= "<img src='{$filename}' alt='{$clients[$i]->firstname}' class='clients img-rounded' /></a>";
	
		$clients[$i]->encodedBy = $db->selectValue("users", "firstname", "id ={$clients[$i]->createdBy}");
		
		$clients[$i]->performance['balance'] 		= getClientBalance($clients[$i]->id);
		$clients[$i]->performance['last_payment'] 	= getLastPayment($clients[$i]->id);;
		$clients[$i]->performance['current_loan'] 	= getLoan($clients[$i]->id);
		$clients[$i]->performance['type'] 			= getLoanType($clients[$i]->id);
		$clients[$i]->performance['loan_due'] 		= getLoanDueDate($clients[$i]->id);
		$clients[$i]->performance['cycle'] 			= getNumberOfLoanCycle($clients[$i]->id);
		$clients[$i]->performance['collector'] 		= getCollectorName($clients[$i]->user_id);
		
		$clients[$i]->businessInfo 					= getBusinessInformation($clients[$i]);
	}
	
	//Filters
	//Get the collectors
	$collectors = $db->selectObjects("users","user_type='2' AND active = 1", "lastname");
	//Get the alphabets
	$alphas 	= range('A', 'Z');
	//Get the categories
	$categories = $db->selectColumn("categories", "category");
	
	//Set the pageheader and include the header
	$pageheader = "Clients";
	include('header.php');
?>

	<div class="row-fluid">
		<div class="span3">
			<ul class="nav nav-tabs nav-stacked">
			
				
				
				<div class="accordion" id="accordion2">
				
					<!-- Accordion for collectors -->
			 		<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_collectors">
						    	Salesmen
						 	</a>
						</div>

						<div id="collapse_collectors" class="accordion-body collapse <?php if($_GET['collector']) echo "in"; ?>">
							<div class="accordion-inner">
								<ul class="nav nav-tabs nav-stacked">
									<?php foreach($collectors as $collector) : ?>
									<li <?php if($_GET['collector'] == $collector->id) echo "class='active'"; ?>><a href="clients.php?collector=<?php echo $collector->id; ?>"><?php echo $collector->firstname . ' ' . $collector->lastname; ?></a></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
				 	</div>
					<!-- End collector accordion here -->
	
				
				
				</div>				
			</ul>
		</div>
			
		<div class="span9">
				<table class="table table-hover client span12">
					<thead>
						<tr>
							<th style="width: 20%">Client</th>
							<th style="width: 43%">Loan Performance</th>
							<th style="width: 30%">Business Information</th>
							<th style="width: 7%">Encoder</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($clients as $item): ?>
						<tr>
							 
							<td>
								<p class="text-center"><?php echo $item->image; ?></p>
								<p class="text-center" style="margin-bottom:0"><?php echo $item->name; ?></p>
							<td>
							
								
								<table class="loan_performance table-condensed">
							
									<tbody>
										
										<tr>
											<td class="labeled">Balance:</td>
											<td><?php echo formatToAmount($item->performance['balance']) ; ?></td>
										</tr>
										<tr>
											<td class="labeled">Payment:</td>
											<td><?php echo $item->performance['last_payment'] ; ?></td>
										</tr>
										<tr>
											<td class="labeled">Loan:</td>
											<td><?php echo $item->performance['current_loan'] ; ?></td>
										</tr>
								
										<tr>
											<td class="labeled">Loan Due:</td>
											<td><?php echo $item->performance['loan_due'] ; ?></td>
										</tr>
									</tbody>
								</table>
								
							</td>
						
							<td>
								<?php echo $item->businessInfo; ?>
								
							</td>
							<td><p><?php echo $item->encodedBy; ?></p></td>
						</tr>
					
						<?php endforeach; ?>
					</tbody>

					
			</table>
				<?php echo paginate($statement,$limit,$page, $url); ?> 
		</div>
	</div>
	<!-- Include the footer -->
	<?php include('footer.php'); ?>