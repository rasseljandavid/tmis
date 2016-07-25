<?php

if(!empty($_POST['submit'])) {
	
	if(!empty($_POST['cust'])) {
		foreach($_POST['cust'] as $key => $item) {
			
			
			$data = getSalesOrderByID($key);
		
			$data = current($data);
			$data->SalesOrderStatus = "Closed";
		
			
			$arr = "";
			$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
			$arr['mvSalesOrder']  = $data;
			$arr['mvRecordAction']  = "Update";
			
			echo "<pre>";
			print_r($arr);
			exit();
			
			$str = json_encode($arr);


			$ch = curl_init();
			$headers = array('Accept: application/json','Content-Type: application/json'); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SalesOrderUpdate?format=json");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);

			$res = json_decode($server_output);
			
			echo "<pre>";;
			print_r($res);
			
			exit();
			curl_close ($ch);
		}
	}
	exit();
}


$customers_arr = getCustomers();
$customers = array();

foreach($customers_arr as $item) {
	$customers[$item->SupplierClientID] = $item->SupplierClientName;
}

$arr = getSalesOrder();

$data = array();

foreach($arr as $item) {  
	if(($item->SalesOrderStatus == "Closed" || $item->SalesOrderStatus == "FullyInvoiced" || $item->SalesOrderStatus == "PartiallyShippedAndPartiallyInvoiced") && $item->SalesOrderContactPerson != "") {
		$total_sales = 0;
		$total_book  = 0;
		$customers_day = array();
		
		$temp_products = array();
		$i = 0;
		foreach($item->SalesOrderDetails as $item2) {
			
			$total_sales +=  $item2->SalesOrderRowUnitPriceWithoutTaxOrDiscount * $item2->SalesOrderRowShippedQuantity;
			$total_book  +=  $item2->SalesOrderRowTotalAmount;
			
			@$temp_products[$i]->name = $item2->SalesOrderRowProductDescription;
			$temp_products[$i]->book = $item2->SalesOrderRowQuantity;
			$temp_products[$i]->sale = $item2->SalesOrderRowShippedQuantity;
			$i++;
		
		}
		
		@$data[convertDate($item->SalesOrderDate)][$item->SalesOrderContactPerson]->total_sales += $total_sales;
		@$data[convertDate($item->SalesOrderDate)][$item->SalesOrderContactPerson]->total_book  += $total_book;
		$temp_customers = "";
		@$temp_customers->name = $customers[$item->SalesOrderClientID];
		$temp_customers->id = $item->SalesOrderId;
		$temp_customers->book = $total_book;
		$temp_customers->sale = $total_sales;
		$temp_customers->no = $item->SalesOrderNo;
		
		$temp_customers->status = $item->SalesOrderStatus;
		
		$temp_customers->products = $temp_products;
		@$data[convertDate($item->SalesOrderDate)][$item->SalesOrderContactPerson]->customers[]    = $temp_customers;
	
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>
	<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
<table class='table table-striped table-bordered' style="width: 80%; margin: auto; margin-bottom: 20px;">
	<tr>
		<th>Date</th>
		<th>El Booking/Sales</th>
		<th>Norman Booking/Sales</th>
		<th>Jason Booking/Sales</th>
		<th>Total Booking/Sales</th>
	</tr>

<?php foreach($data as $key => $item) : ?>
	<?php
		$date_total_book  = $item['El Ramos']->total_book  +  $item['Norman Bognot']->total_book + $item['Jason Mercado']->total_book;
		$date_total_sales = $item['El Ramos']->total_sales +  $item['Norman Bognot']->total_sales  + $item['Jason Mercado']->total_sales
	
	?>
	<tr>
		<td><?php echo $key; ?></td>
		<td><a href="javascript:;" data-toggle="modal" data-target="#<?php echo $key;?>_El_Ramos">P<?php echo number_format($item['El Ramos']->total_book, 2);?> / P<?php echo number_format($item['El Ramos']->total_sales, 2);?></a>
			<div id="<?php echo $key;?>_El_Ramos" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title">El Ramos Booking and Sales on <?php echo $key?></h4>
					      </div>
				 					      <div class="modal-body">
				 							 <ul class="nav nav-stacked">
				 			<?php if(count($item['El Ramos']->customers) > 0) foreach($item['El Ramos']->customers as $cust) : ?>
				 				<li><strong><a href="#" data-toggle="collapse" data-target="#<?php echo $key;?>_El_Ramos_<?php echo $cust->id; ?>"><?php echo $cust->name . " (P" . number_format($cust->book, 2) . " / P" . number_format($cust->sale, 2); ?>)</a></strong>
				 					<div id="<?php echo $key;?>_El_Ramos_<?php echo $cust->id; ?>" class="collapse">
				 						<ul>
				 						<?php  foreach($cust->products as $prods) : ?>
				 							<li><span style="<?php if($prods->book != $prods->sale) echo "color: #b94a48;"; ?>"><?php echo $prods->name . " (" . $prods->book  . " / " . $prods->sale . ")"; ?></span></li>
				 						<?php endforeach?>
				 						</ul>
				 					</div>
				 				</li>
				 			<?php endforeach; ?>
				 		</ul>
				 					</div>
				</div>
			</div>
		</td>
		<td><a href="javascript:;" data-toggle="modal" data-target="#<?php echo $key;?>_Norman_Bognot">P<?php echo number_format($item['Norman Bognot']->total_book, 2); ?> / P<?php echo number_format( $item['Norman Bognot']->total_sales, 2);?></a>
			<div id="<?php echo $key;?>_Norman_Bognot" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title">Norman Bognot Booking and Sales on <?php echo $key?></h4>
					      </div>
					      <div class="modal-body">
							 <ul class="nav nav-stacked">
			<?php if(count($item['Norman Bognot']->customers) > 0) foreach($item['Norman Bognot']->customers as $cust) : ?>
				<li><strong><a href="#" data-toggle="collapse" data-target="#<?php echo $key;?>_Norman_Bognot_<?php echo $cust->id; ?>"><?php echo $cust->name . " (P" . number_format($cust->book, 2) . " / P" . number_format($cust->sale, 2); ?>)</a></strong>
					<div id="<?php echo $key;?>_Norman_Bognot_<?php echo $cust->id; ?>" class="collapse">
						<ul>
						<?php  foreach($cust->products as $prods) : ?>
							<li><span style="<?php if($prods->book != $prods->sale) echo "color: #b94a48;"; ?>"><?php echo $prods->name . " (" . $prods->book  . " / " . $prods->sale . ")"; ?></span></li>
						<?php endforeach?>
						</ul>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
					</div>
				</div>
			</div>
		</td>
		<td><a href="javascript:;" data-toggle="modal" data-target="#<?php echo $key;?>_Jason_Mercado">P<?php echo number_format($item['Jason Mercado']->total_book, 2); ?> / P<?php echo number_format($item['Jason Mercado']->total_sales, 2);?></a>
			<div id="<?php echo $key;?>_Jason_Mercado" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						  <form action="<?php echo $_SERVER['PHP_SELF'] ; ?>" method="post">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal">&times;</button>
					        <h4 class="modal-title">Jason Mercado Booking and Sales on <?php echo $key?></h4>
					      </div>
					        <div class="modal-body">
								
								<table class="table">
									<thead>
										<tr>
											<th><input type="checkbox" class="checkAll" /></th>
											<th>Client</th>
											<th>Total Booking</th>
											<th>total Sales</th>
											<th>Order Status</th>
										</tr>
									</thead>
									
									<tbody>
										<?php if(count($item['Jason Mercado']->customers) > 0) foreach($item['Jason Mercado']->customers as $cust) : ?>
											<tr>
												<td><input type="checkbox" name="cust[<?php echo $cust->no; ?>]" /></td>
												<td><strong><a href="#" data-toggle="collapse" data-target="#<?php echo $key;?>_Jason_Mercado_<?php echo $cust->id; ?>"><?php echo $cust->name; ?></a></strong>
							 				
												</td>
												<td>P<?php echo number_format($cust->book, 2); ?></td>
												<td>P<?php echo number_format($cust->sale, 2); ?></td>
												<td><?php echo $cust->status; ?></td>
											</tr>
											
											<tr>
												<td colspan=5>
								 					<div id="<?php echo $key;?>_Jason_Mercado_<?php echo $cust->id; ?>" class="collapse">
								 						<ul>
								 						<?php  foreach($cust->products as $prods) : ?>
								 							<li><span style="<?php if($prods->book != $prods->sale) echo "color: #b94a48;"; ?>"><?php echo $prods->name . " (" . $prods->book  . " / " . $prods->sale . ")"; ?></span></li>
								 						<?php endforeach?>
								 						</ul>
								 					</div>
													
												</td>
											</tr>
											
										<?php endforeach; ?>
									</tbody>
									
								</table>
				 			</div>
							
							
  					      <div class="modal-footer">
							  <input type="submit" name="submit" class="btn btn-primary" value="Closed Order" />
  					      </div>
						</form>
				</div>
			</div>
		</td>
		<td>P<?php echo number_Format($date_total_book, 2); ?> / P<?php echo number_Format($date_total_sales, 2); ?></td>
	</tr>
<?php endforeach; ?>
</table>
<script type="text/javascript">
$('.checkAll').click(function () {    
    $('input:checkbox').prop('checked', this.checked);    
});
	
	
</script>
</body>
</html>

<?php
function getSalesOrder() {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	//$arr['query']  = "mv.SalesOrderStatus = 'FullyInvoiced'";
	$str = json_encode($arr);


	$ch = curl_init();
	$headers = array('Accept: application/json','Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SalesOrderGet?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	$res = json_decode($server_output);

	curl_close ($ch);
	
	return $res->mvSalesOrders;
}	


function getSalesOrderByID($sid) {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['mvSalesOrderNo'] = $sid;
	//$arr['query']  = "mv.SalesOrderStatus = 'FullyInvoiced'";
	$str = json_encode($arr);


	$ch = curl_init();
	$headers = array('Accept: application/json','Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SalesOrderGet?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	$res = json_decode($server_output);
	
	curl_close ($ch);
	
	return $res->mvSalesOrders;
}	

function convertDate($date) {
//	$date = '/Date(1440190800000-0000)/'; 
	preg_match('/(\d{10})(\d{3})([\+\-]\d{4})/', $date, $matches); 

	// Get the timestamp as the TS tring / 1000 
	$ts = (int) $matches[1]; 

	// Get the timezone name by offset 
	$tz = (int) $matches[3]; 
	$tz = timezone_name_from_abbr("", $tz / 100 * 3600, false); 
$tz= "Asia/Manila";
	$tz = new DateTimeZone($tz); 

	// Create a new DateTime, set the timestamp and the timezone 
	$dt = new DateTime(); 
	$dt->setTimestamp($ts); 
	$dt->setTimezone($tz); 

	// Echo the formatted value  
	return $dt->format('Y-m-d');  
}

function getCustomers() {
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['query'] = "mv.SupplierClientType = 2";
	$str = json_encode($arr);


	$ch = curl_init();
	$headers = array('Accept: application/json','Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SupplierClientGet?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	$res = json_decode($server_output);

	curl_close ($ch);
	
	return $res->mvSupplierClients;
}