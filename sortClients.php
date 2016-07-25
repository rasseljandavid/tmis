<?php
 
	//Connect to DB
	include('config.php');
	
	if(!empty($_GET['id'])) {		
		$id = (int)$_GET['id'];
		$additional_condition = " AND user_id = {$id}";
	} else {
		$additional_condition = " AND user_id = 8";
	}
	$clients = $db->selectObjects("clients", "active = 1{$additional_condition} ", "position asc");

	$collectors = $db->selectObjects("users", "active = 1 AND user_type = '2'", "lastname");

	$pageheader = "Sort Clients";

	//Include the header and the navigation
	include('header.php');
?>
	<div class="row-fluid">
		<div class="span3">

			<ul class="nav nav-tabs nav-stacked">
				<?php foreach($collectors as $item) : ?>
					
				<?php if($item->id == $_GET['id']) : ?>
					<li class="<?php if($item->id == $_GET['id']) echo "active"; ?>"><a href="javascript:;"><?php echo $item->firstname . ' ' . $item->lastname; ?></a></li>
				<?php else : ?>
					<li class="<?php if($item->id == $_GET['id']) echo "active"; ?>"><a href="sortClients.php?id=<?php echo $item->id; ?>"><?php echo $item->firstname . ' ' . $item->lastname; ?></a></li>
				<?php endif;?>
				
				<?php endforeach; ?>
			</ul>			
		</div>
			
		<div class="span9">
			
			<ul id="client-list" class="unstyled">
			<?php 
				foreach($clients as $item):
				
				$item->image = $configs['client_image_directory'] . $db->selectValue("images", "filename", "client_id = {$item->id}");

				if(!file_exists($item->image) || is_dir($item->image)) {
					$item->image = "images/default.png";
				}
			 ?>
				<li id="listItem_<?php echo $item->id; ?>">
					<i class="icon-move handle"></i>
					<img class='clients img-rounded' style="width: 50px; height: 50px; margin-right: 10px;" src="<?php echo $item->image; ?>" />
					<?php echo $item->lastname; ?>, <?php echo $item->firstname; ?> <?php echo $item->middlename; ?></li>
				  
			<?php endforeach; ?>
			</ul>
		</div>
		
	</div>
	<!-- Include the footer -->
	<?php include('footer.php'); ?>

	<script type="text/javascript">
	  // When the document is ready set up our sortable with it's inherant function(s)
	  $(document).ready(function() {
	    $("#client-list").sortable({
	      handle : '.handle',
	      update : function () {
			  var order = $('#client-list').sortable('serialize');
	  		
	
			 var request = $.ajax({
			  url: "lib/ajax/ajaxsort.php?" + order,
			  type: "GET"
			}).done(function( data) {
     			$(".alert").remove();
				$(".hero .container:first-child").prepend(data);

			});
	
	      }
	    });
	});
	</script>