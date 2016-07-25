<?php
	include_once('../config.php');
	
	if(!empty($_GET['payment_id']))	{
		$payment = $db->selectObject("payments", "id = {$_GET['payment_id']}  AND active = 1");
		$loans   = $db->selectObjects("loans", "id = {$payment->loan_id}  AND active = 1");
		$client_id = $payment->client_id;
		$payment->capital  = formatToAmount($payment->capital, null);
		$payment->interest = formatToAmount($payment->interest, null);
	}
	if(!empty($_GET['client_id'])) {
		$loans = $db->selectObjects("loans", "client_id =" . $_GET['client_id'] . " AND paid = 0  AND active = 1", "loanDate DESC");
		$client_id = $_GET['client_id'];
	}
	
?>
	<?php if(empty($loans) && empty($client_id)) : ?>
		<input placeholder="Type account name here..."  type="text" id="user-input" autocomplete="off" required  class="input-block-level" />
		<input type="hidden" name="client_id" id="client_id" value="" required />
	<?php else: ?>
		<input type="hidden" name="client_id" id="client_id" value="<?php echo $client_id; ?>" required />
	<?php endif; ?>
	
<input type="hidden" name="payment_id" id="payment_id" value="<?php echo $payment->id; ?>" />
	<input placeholder="Amount" type="text" name="capital" id="capital" class="input-block-level" value="<?php echo @$payment->capital; ?>" required />

	
	<input type="text" placeholder="Date" name="paymentDate" class="input-block-level paymentDate" autocomplete="off"  value="<?php echo @$payment->paymentDate; ?>" required />
	<textarea name="remarks" placeholder="Remarks" id="remarks" cols="30" rows="10" class="input-block-level"><?php echo @$payment->remarks; ?></textarea>
	
	<script type="text/javascript">
	
	$(function() {
		var users = {};
		var userLabels = [];    
			
		var searchPeople = _.debounce(function(  query, process ) {

		//the "process" argument is a callback, expecting an array of values (strings) to display

		//get the data to populate the typeahead (plus some) 
		//from your api, wherever that may be
		$.get( 'lib/ajax/ajaxgetclient.php', { q: query, t: 'payment' }, function ( data ) {
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
						collector: item.collector,
						loans: item.loans
					};
						
					//add the label to the display array
					userLabels.push( item.name );
				});
						
				//return the display array	
				process( userLabels );
		
			});
		
		}, 300);

			
		$("#user-input").typeahead( {
			source: function ( query, process ) { searchPeople( query, process );},
			updater: function (item) {
			
				if(users[item].loans != undefined) {
					$.each(users[ item ].loans, function(key,val) {
						$("#loan_id").append('<option value=' + val.id + '>' + val.label + '</option>');
					 });
				}
				
				
				$("#client_id").val( users[ item ].id );
				
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
		
		$( ".paymentDate" ).datetimepicker();
		$(".select_placeholder").on("change", function () {
			if($(this).val() == "") $(this).addClass("empty");
			else $(this).removeClass("empty")
		});
		$(".select_placeholder").change();
		if($("input.interest").val() == "") {
			$("#interest").css("display", "none");
			$("#showInterest").find(".icon-arrow-up").removeClass("icon-arrow-up").addClass("icon-arrow-down");
			$("#capital").attr("name", "amount");
		}
		$("#showInterest").click(function() {
			if($("#interest").css("display") == "none") {
				$("#capital").attr("placeholder", "Capital");
				$("#capital").attr("name", "capital");
				$("#interest").slideDown().find("input.interest").attr("required", "required");
				$(this).find(".icon-arrow-down").removeClass("icon-arrow-down").addClass("icon-arrow-up");
			} else {
				$("#capital").attr("placeholder", "Amount");
				$("#capital").attr("name", "amount");
				$("#interest").slideUp().find("input.interest").removeAttr('required')
				$(this).find(".icon-arrow-up").removeClass("icon-arrow-up").addClass("icon-arrow-down");
			}
		});
	});	
			
	</script>