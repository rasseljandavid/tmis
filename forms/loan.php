<?php
	include_once('../config.php');
	
	//If editing...
	if(!empty($_GET['loan_id']))	{
		$loan = $db->selectObject("loans", "id = {$_GET['loan_id']}  AND active = 1");
		$client_id = $loan->client_id;
		$loan->capital = formatToAmount($loan->capital, null);
	}
	
	//If adding to a particular client
	if(!empty($_GET['client_id'])) {
		$client = $db->selectObject("clients", "id ={$_GET['client_id']}");
		$client_id = $client->id;
	}
	
	//Select the categories (Daily, Weekly, Semi, Monthly)
	$categories    = $db->selectObjects("categories");
	
	//Get the months
	$months 	   = getNumberOfMonths();
	
	//Get the interest rates
	$interestRates = getInterestRates();

?>
	<?php if(empty($loan) && empty($client)) : ?>
   		<input placeholder="Type client name here..."  type="text" id="user-input" autocomplete="off" required  class="input-block-level" />
		<input type="hidden" name="client_id" id="client_id" value="" required />
	<?php else: ?>
		<input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id; ?>" required />
		<input type="hidden" name="loan_id" id="loan_id" value="<?php echo @$loan->id; ?>" />
	<?php endif; ?>		
	<input placeholder="Loan amount" type="text" name="capital" id="capital" class="input-block-level" value="<?php echo @$loan->capital; ?>" required />
			
	<select name="interest" id="interest" class="input-block-level select_placeholder" required>
		<option value="" selected>Interest rate</option>		
		<?php foreach($interestRates as $rate) : ?>
				<option value="<?php echo $rate; ?>" <?php if($rate == $loan->interest) echo "selected"; ?>><?php echo formatToPercent($rate); ?></option>
		<?php endforeach; ?>
	</select>
			
	<select name="monthstopay" id="monthstopay" class="input-block-level select_placeholder" required>
		<option value="" selected>Number of months</option>
		<?php foreach($months as $month) : ?>
			<option value="<?php echo $month; ?>" <?php if($month == $loan->monthsToPay) echo "selected"; ?>><?php echo $month; ?></option>
		<?php endforeach; ?>
	</select>

	<select name="category_id" id="category_id" class="input-block-level select_placeholder" required>
		<option value="" selected>Category</option>
		<?php foreach($categories as $category) : ?>
			<option value="<?php echo $category->id; ?>" <?php if($category->id == $loan->category_id) echo "selected"; ?>><?php echo $category->category; ?></option>
		<?php endforeach; ?>
	</select>
			
	<input type="text" placeholder="Date" name="loanDate" class="input-block-level loanDate" autocomplete="off"  value="<?php echo @$loan->loanDate; ?>" required />
	<textarea name="remarks" placeholder="Remarks" id="remarks" cols="30" rows="10" class="input-block-level"><?php echo @$loan->remarks; ?></textarea>

	<script type="text/javascript">
		var users = {};
		var userLabels = [];    
			
		var searchPeople = _.debounce(function(  query, process ) {

		//the "process" argument is a callback, expecting an array of values (strings) to display

		//get the data to populate the typeahead (plus some) 
		//from your api, wherever that may be
		$.get( 'lib/ajax/ajaxgetclient.php', { q: query }, function ( data ) {
		//reset these containers
			users = {};
			userLabels = [];
									
			//for each item returned, if the display name is already included 
			//(e.g. multiple "John Smith" records) then add a unique value to the end
			//so that the user can tell them apart. Using underscore.js for a functional approach.  
				_.each( data, function( item, ix, list ) {
					if ( _.contains( users, item.name ) ){
						item.name = item.name + ' #' + item.id;
					}
						
					//also store a mapping to get from label back to ID
					users[ item.name ] = {
						id: item.id,
						name: item.name,
						image: item.image,
						collector: item.collector
					};
						
					//add the label to the display array
					userLabels.push( item.name );
				});
						
				//return the display array	
				process( userLabels );
		
			});
		
		}, 300);

			
		$( "#user-input" ).typeahead( {
			source: function ( query, process ) { searchPeople( query, process );},
			updater: function (item) {
				$( "#client_id" ).val( users[ item ].id );
			        	return item;
			    },
			matcher: function () { return true; },
			highlighter: function(item){
				var p = users[ item ];
				var itm = ''
					+ "<div class='typeahead_wrapper'>"
					+ "<img class='typeahead_photo' src='" + p.image + "' />"
					+ "<div class='typeahead_labels'>"
					+ "<div class='typeahead_primary'>" + p.name + "</div>"
					+ "<div class='typeahead_secondary'>" + p.collector + "</div>"
					+ "</div>"
					+ "</div>";
				return itm;
			 }
		});
		
		$( ".loanDate" ).datetimepicker();
		$(".select_placeholder").on("change", function () {
			if($(this).val() == "") $(this).addClass("empty");
			else $(this).removeClass("empty")
		});
		$(".select_placeholder").change();
			
	</script>