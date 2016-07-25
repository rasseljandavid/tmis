<?php
	require_once('../exponent.php');
	$direct_distributors = $db->selectObjects("companies", "is_direct");
	
	global $user;
	
	if($user->is_admin) :
	
	
?>
	<table class="table">
		<tbody>
			<?php foreach($direct_distributors as $dist) : ?>
				<tr>
					<td colspan="3"><strong><?php echo $dist->title; ?></strong></td>
				</tr>
				<?php
				$products =	$db->selectObjects("product", "companies_id = {$dist->id}");
			
				foreach($products as $product) :
				?>
				<tr>
					<td><?php echo $product->title; ?> <?php echo $product->capacity; ?></td>
					<td>P<?php echo number_format($product->manufacturing_price, 2); ?></td>
					<td>P<?php echo number_format($product->manufacturing_price * 1.05, 2); ?></td>
				</tr>
				
				<?php endforeach; ?>
			<?php endforeach; ?>
		</tbody>
		
		
	</table>
	
	
<?php endif; ?>