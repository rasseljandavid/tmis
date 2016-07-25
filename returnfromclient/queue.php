<?php

	global $router, $db;
	include_once('exponent.php');
	

	if(!empty($_GET['action'])) {
		$action = $_GET['action'];
		$id = (int)$_GET['id'];
		if($action == "delete") {
			 $db->delete("queue", "id = {$id}");
		} elseif($action == "send") {
			
			$sales_order = $db->selectObject("queue", "id = {$id}");
			
			$sale_arr = json_decode($sales_order->sales_order);
			
			
			$ideals = $db->selectObjects("pos");
			$pos = "";
			$is_change = false;
			
			//Check if this current so has ideal template
			foreach($ideals as $item) {
				
				$temp_pos = json_decode($item->sale_order);
			
				if($temp_pos->SalesOrderClientID == $sale_arr->mvSalesOrder->SalesOrderClientID) {
					$pos = $temp_pos;
					break;
				}
			}
		
			if(!empty($pos)) {
			foreach($sale_arr->mvSalesOrder->SalesOrderDetails as $item) {
			
				$found = false;
				foreach($pos->SalesOrderDetails as &$item2) {
					if($item2->SalesOrderRowProductSKU == $item->SalesOrderRowProductSKU) {
						$found = true;
						//if it is greater then assign the new quantity
						if($item->SalesOrderRowQuantity > $item2->SalesOrderRowQuantity) {
							$item2->SalesOrderRowQuantity = $item->SalesOrderRowQuantity;
							$is_change = true;
						}
						break;
					}
				}
				//if the item is not in the pos, add it
				if($found == false) {
					$is_change = true;
					$pos->SalesOrderDetails[] = $item;
				}
			}
		
		
			
			if(!empty($pos) && $is_change) {
				//update the pos
			
				$pos->SalesOrderStatus = "Pending";
				$arr = "";
				$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
				$arr['mvSalesOrder']  = $pos;
				//$arr['mvSalesOrder']
				$arr['mvRecordAction']  = "Update";
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
				if($res->ResponseStatus->ErrorCode == "500") {
				
					echo "<p>Please revert the SO to pending state first before continuing.</p>" ;
					echo "<p>Hit the refresh when you are done</p>";
					echo "<pre>";
					print_r($res);
					exit();
				}
			}
		
			}
			$ch = curl_init();
			$headers = array('Accept: application/json','Content-Type: application/json'); 
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SalesOrderUpdate?format=json");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$sales_order->sales_order);


			// receive server response ...
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$server_output = curl_exec ($ch);

			$res = json_decode($server_output);
			if($res) {
				 $db->delete("queue", "id = {$id}");
			}
		}
		
	}

	
	$sales_orders = $db->selectObjects("queue");
	
	foreach($sales_orders as $so) {
		$data[$so->id] = json_decode($so->sales_order);
	}
	
	$customers_obj = $db->selectObjects("customers");
	$customers = "";
	foreach($customers_obj as $item) {
		$customers[$item->id] = $item->cust_name;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Local Tienda Interface</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" media="all" />
	<link rel="stylesheet" type="text/css" href="css/jquery.typeahead.css" media="all" />
    <style>
        /* Extra styles to adjust Typeahead */
        .typeahead-container {
            max-width: 500px;
        }
    </style>
	
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/jquery.typeahead.js"></script>
	<script type="text/javascript">
	
			function printDiv(divName) {
			     var printContents = document.getElementById(divName).innerHTML;
			     var originalContents = document.body.innerHTML;

			     document.body.innerHTML = printContents;

			     window.print();

			     document.body.innerHTML = originalContents;
			}
		
	</script>
</head>
<body>
	<div class="row container" style="margin: auto; position: relative; z-index: 2;">
		<h2 style="text-align: center;">Send To Megaventory</h2>
	
		<?php if(!empty($data)) : ?>
		<form id="submit_to_megaventory" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="row container">
				<table class="table table-bordered table-striped" id="product_table">
					<thead>
						<tr>
						
							<th>Date</th>
							<th>Client</th>
							<th>Total</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($data as $key => $item): ?>
					
						<tr>
							<td><?php echo str_replace("'", "", str_replace("DATETIME", "",$item->mvSalesOrder->SalesOrderDate)); ?></td>
							<td id="sale_<?php echo $item->mvSalesOrder->SalesOrderClientID;?>">
							
								<?php echo $customers[$item->mvSalesOrder->SalesOrderClientID]; ?>
								<ul>
								<?php foreach($item->mvSalesOrder->SalesOrderDetails as $item2) :?>
									<li><?php echo $item2->SalesOrderRowProductDescription . " - " . $item2->SalesOrderRowQuantity?>pcs</li>
								<?php endforeach; ?>
								</ul>
							</td>
							<td>P<?php echo number_format($item->mvSalesOrder->SalesOrderAmountGrandTotal, 2); ?></td>
							<td><a onClick="return confirm('Send Entry?')" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=send&id=<?php echo $key; ?>">Send</a> | <a onclick="printDiv('sale_<?php echo $item->mvSalesOrder->SalesOrderClientID;?>')" href="javascript:;">Print</a> | <a onClick="return confirm('Are you sure you want to delete?')" href="<?php echo $_SERVER['PHP_SELF']; ?>?action=delete&id=<?php echo $key; ?>">Delete</a></td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</form>
		<?php else: ?>
			<p>No Pending SO to be send</p>
		<?php endif; ?>
		<p style="text-align: center;"><a class="btn btn-default" href="index.php">Return to Home</a></p>
		<hr />
		
	</div>
</body>
</html>