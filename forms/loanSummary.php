<?php
	include_once('../config.php');

	global $db;

	$date = $_GET['date'];
	$loans = $db->selectObjects("loans", "active = 1 AND DATE_FORMAT(loanDate, '%M-%d-%Y') = '{$date}'");

	for($i = 0; $i < count($loans); $i++) {
		$loans[$i]->name   = getClientName($loans[$i]->client_id);
		$loans[$i]->amount = formatToAmount($loans[$i]->capital);
	}
	
?>

<table class="table">
	<thead>
		<tr>
			<td>Name</td>
			<td>Amount</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach($loans as $item): ?>
			<tr>
				<td><?php echo $item->name; ?></td>
				<td><?php echo $item->amount; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	
</table>