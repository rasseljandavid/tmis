<?php
	include_once('../config.php');

	global $db;

	$date = $_GET['date'];
	$payments = $db->selectObjectsBySql("SELECT client_id, capital, interest, user_id FROM payments, clients WHERE client_id = clients.id AND payments.active = 1 AND DATE_FORMAT(paymentDate, '%M-%d-%Y') = '{$date}'");
	$data  = array();
	$total = array();
	for($i = 0; $i < count($payments); $i++) {
		@$data[$payments[$i]->user_id][$i]->name     = getClientName($payments[$i]->client_id);
		$data[$payments[$i]->user_id][$i]->total    = formatToAmount($payments[$i]->capital + $payments[$i]->interest);
		$data[$payments[$i]->user_id][$i]->capital  = formatToAmount($payments[$i]->capital);
		$data[$payments[$i]->user_id][$i]->interest = formatToAmount($payments[$i]->interest);
		@$total[$payments[$i]->user_id]->capital += $payments[$i]->capital;
		$total[$payments[$i]->user_id]->interest += $payments[$i]->interest;
		$total[$payments[$i]->user_id]->total += $payments[$i]->capital + $payments[$i]->interest;
	}

	foreach($data as $key => $value) {
		$collectors[] = $key;
	}
//	$collectors = $db->selectObjects("users", "active = 1 and user_type = 2");

	
?>
<ul class="nav nav-tabs" id="myTab">
	<?php foreach($collectors as $item): ?>
		 <li><a href="#collector_<?php echo $item; ?>" data-toggle="tab"><?php echo getCollectorName($item); ?></a></li>
	<?php endforeach;?>
</ul>


<div class="tab-content">
  	<?php foreach($collectors as $item): ?>
		<div class="tab-pane" id="collector_<?php echo $item; ?>">
			
			<table class="table">
				<thead>
					<tr>
						<td>Name</td>
						<td>Collection</td>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data[$item] as $payment) : ?>
						<tr>
							<td><?php echo $payment->name; ?></td>
							<td><?php echo $payment->capital; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfooter>
					<th>Total:</th>
					<td><?php echo 	formatToAmount($total[$item]->capital); ?></td>
				</tfooter>
				
			</table>
		</div>
	<?php endforeach;?>
</div>

<script>
  $(function () {
    $('#myTab a:first').tab('show');
  })
</script>