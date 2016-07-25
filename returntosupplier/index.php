<?php
global $router, $db;
include_once('exponent.php');

if(!empty($_POST['submit'])) {
	
	
	$supplier_id = $_POST['SalesOrderClientID'];
	$input = array();
	foreach($_POST['products'] as $item) {
		$input[$item['sku']] = $item['qty'];
	}
	
	
	
	
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	$arr['query'] = "mv.DocumentSupplierClientID={$supplier_id}";

	$str = json_encode($arr);

	$ch = curl_init();
	$headers = array('Accept: application/json','Content-Type: application/json'); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/DocumentGet?format=json");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


	// receive server response ...
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec ($ch);

	$res = json_decode($server_output);

	curl_close ($ch);
	$inbound = array();
	$return  = array();
	$i=0;
	foreach($res->mvDocuments as $item) {

		if(trim($item->DocumentTypeAbbreviation) == "GIPI" || trim($item->DocumentTypeAbbreviation) == "GRSCR") {

	
			if($item->DocumentStatus == "Verified" || $item->DocumentStatus=="Closed") {
				if(trim($item->DocumentTypeAbbreviation) == "GRSCR") {
	
					@$return[$item->DocumentId]->document_parent = $item->DocumentParentDocId;
					$return[$item->DocumentId]->products = $item->DocumentDetails;
			
				}
		
				if(trim($item->DocumentTypeAbbreviation) == "GIPI") {
		
					$inbound_prod_arrays = array();
					foreach($item->DocumentDetails as $inbound_item) {
						$inbound_prod_arrays[$inbound_item->DocumentRowProductSKU] = $inbound_item->DocumentRowQuantity;
				//		$inbound_prod_arrays[$inbound_item->DocumentRowProductSKU]
					}
					@$inbound[$item->DocumentId]->documentNo = $item->DocumentNo;
					$inbound[$item->DocumentId]->products   = $inbound_prod_arrays;
				}
		
				//@$data[$supplier->SupplierClientName][$item->DocumentNo][] = $item;
				//$data[$supplier->SupplierClientName][convertDate($item->DocumentDate)]->color  = $supplier->SupplierClientComments;
			}
		}
	}

	foreach($return as $item) {
		foreach($item->products as $item2) {
			$inbound[$item->document_parent]->products[$item2->DocumentRowProductSKU] = $inbound[$item->document_parent]->products[$item2->DocumentRowProductSKU] - $item2->DocumentRowQuantity;
		}
	}
	$final_inbound = array();
	foreach($inbound as $item) {
		$final_inbound[$item->documentNo] = $item->products;
	}


	
	$data = array();

	foreach($input as $key => $item) {
		foreach($final_inbound as $key2 => $item2) {
			
			
			if(@$item2[$key] >= $item) {
				$temp = "";
				@$temp->sku = $key;
				$temp->qty = $item;
				
				$data[$key2][] = $temp;
				break;
				
			}
		}
	}
}



$customers_obj = $db->selectObjects("companies");

$customers = "";
foreach($customers_obj as $item) {
	$customers[$item->id] = '"' . $item->title . '"';
}

$customer_str = implode(",", $customers);

$products_obj = $db->selectObjects("product");
$products = "";
foreach($products_obj as $item) {
	$products[$item->id] = '"' . $item->title . '"';
}
$product_str = implode(",", $products);



$product_data = array();
foreach($products_obj as $item) {
	$product_data[$item->model] = $item->title;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Return of Goods to Supplier</title>
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
	
</head>
<body>
	
	
	<div class="row container" style="margin: auto; position: relative; z-index: 2;">
		<?php if(empty($data)) : ?>
		
				<?php if(!empty($_GET['update'])) : ?>
				<div class="alert alert-info" role="alert" style="margin-top: 20px;">
					Local Database Updated.
				</div>
				<?php endif; ?>
				<h2 style="text-align: center;">Return of Goods to Supplier</h2>
		
				<div class="col-xs-4">
					<label for="SalesOrderClientID">Select Suppliers</label>
		            <div class="typeahead-container">
		                <div class="typeahead-field">

		                    <span class="typeahead-query">
		                        <input id="q"
		                               name="q"
		                               type="search"
		                               autofocus
		                               autocomplete="off" />
							
		                    </span>
		                    <span class="typeahead-button">
		                        <button class="btn btn-default" type="submit">
		                            <span class="typeahead-search-icon"></span>
		                        </button>
		                    </span>
		                </div>
		            </div>
				</div>
	

			<hr />
	
			</div>
			<div class="row container" style="margin: auto; position: relative; z-index: 1;">
	
			<form id="addnewproduct">
			<div class="row container" style="margin-top: 20px; margin-bottom: 40px;">
				<div class="col-xs-6">
		            <div class="typeahead-container">
		                <div class="typeahead-field">

		                    <span class="typeahead-query">
		                        <input required id="q2"
		                               name="q2"
		                               type="search"
		                               autofocus
		                               autocomplete="off" placeholder="Search Product" />
				
		                    </span>
		                    <span class="typeahead-button">
		                        <button class="btn btn-default" type="submit">
		                            <span class="typeahead-search-icon"></span>
		                        </button>
		                    </span>
							<input type="hidden" name="product_id" value="" id="product_id" />
		                </div>
		            </div>
				</div>
				<div class="col-xs-2">
					<input required type="text" name="newqty" class="form-control" id="newqty" placeholder="Quantity" />
				</div>
				<div class="col-xs-2">
					<input required type="text" name="newprice" class="form-control" id="newprice" placeholder="Price" />
				</div>
		
				<div class="col-xs-2">
					<input type="submit" value="Add" class="btn btn-default" />
				</div>
		
			</div>
			</form>
			<form id="submit_to_megaventory" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
			<div class="row container">
				<table class="table table-bordered table-striped" id="product_table">
					<thead>
						<tr>
							<th>Product</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Sub Total</th>
							<td></td>
						</tr>
					</thead>
					<tbody>
			
					</tbody>
					<tfoot>
						<tr>
							<td></td>
							<td></td>
							<td>Total</td>
							<td><span id="grandtotal">0.00</span></td>
							<td>
								<input type="hidden" name="SalesOrderClientID" id="SalesOrderClientID" value="" />
								<input type="hidden" name="SalesOrderInventoryLocationID_2" id="SalesOrderInventoryLocationID_2" value="" />
								<input type="hidden" name="SalesOrderContactPerson_2" id="SalesOrderContactPerson_2" value="" />
						
								<input type="hidden" name="SalesOrderTotalQuantity" id="SalesOrderTotalQuantity" value="0" />
								<input type="hidden" name="SalesOrderAmountGrandTotal" id="SalesOrderAmountGrandTotal" value="0" />
							</td>
						</tr>
					</tfoot>
				</table>
				<p style="text-align: center;">
							<input type="submit" name="submit" value="Generate" class="btn btn-primary" />
				</p>
		
			</div>
	
			</form>
			<hr />
			<div style="overflow: hidden;">
	
			<p style="float: left;">Click here to <a href="updatePOS.php" onclick="myApp.showPleaseWait();">update</a> your local database</a></p>
			</div>
		<?php else: ?>
			<h2 style="text-align: center;">Return Document Number Generator</h2>
			<table class="table">
				<tbody>
					<?php foreach($data as $key => $item) : ?>
						<tr>
							<th colspan=2>Document No: <?= $key; ?></th>
						</tr>
						<?php foreach($item as $subitem) : ?>
						<tr>
							<td><?= $product_data[$subitem->sku]; ?></td>
							<td><?= $subitem->qty; ?></td>
						</tr>	
						<?php endforeach;?>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
	
	<div class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false">
	        <div class="modal-header">
	            <h1>Processing...</h1>
	        </div>
	        <div class="modal-body">
	            <div class="progress progress-striped active">
	                <div class="bar" style="width: 100%;"></div>
	            </div>
	        </div>
	    </div>
	
	<script type="text/javascript">
		
		var myApp;
		myApp = myApp || (function () {
		    var pleaseWaitDiv = $('<div style="z-index:9999;" class="modal hide" id="pleaseWaitDialog" data-backdrop="static" data-keyboard="false"><div class="modal-header"><h1>Processing...</h1></div><div class="modal-body"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div></div>');
		    return {
		        showPleaseWait: function() {
		            pleaseWaitDiv.modal();
		        },
		        hidePleaseWait: function () {
		            pleaseWaitDiv.modal('hide');
		        },

		    };
		})();

	$customerIds = new Object();
	$productIds = new Object();
	$productTitles = new Object();
	$productPrices = new Object();
	$productSkus = new Object();
	
	
	function computePrice() {
		var sum = 0;
		$('.subtotal').each(function() {
		    sum += parseFloat($(this).text());
		});
		$('#grandtotal').html(sum.toFixed(2))
		
		var quantity = 0;
		$('.prod_qty').each(function() {
		    quantity += parseInt($(this).text());
		});
		$('#SalesOrderTotalQuantity').val(quantity);
		$('#SalesOrderAmountGrandTotal').val(sum.toFixed(2));
		
		
	}
	
	$.ajax({
	    async: false,
	    url: "ajax/getSuppliers.php",
	    success: function(jsonData) {
			 obj = JSON.parse(jsonData);
	
			$.each( obj, function ( index, product ) {
	
				if(product.name != "") {
			    	$customerIds[product.title] = product.id;
				}	
			});
	    }
	});
	
	$.ajax({
	    async: false,
	    url: "ajax/getProducts.php",
	    success: function(jsonData) {
			 obj = JSON.parse(jsonData);
			$.each( obj, function ( index, product ) {
				
			    	$productIds[product.title] = product.id;
					$productTitles[product.title]= product.title;
					$productPrices[product.title] = product.price;
					$productSkus[product.title] = product.sku;
			});
	    }
	});
	
	
	$(function() {   
		
		
		
		var data = {
      
	        "customers": [<?php echo $customer_str; ?>]
	    };

	    $('#q').typeahead({
	        minLength: 1,
	        maxItem: 15,
	        order: "asc",
	        hint: true,
	        backdrop: {
	            "background-color": "#fff"
	        },
	        emptyTemplate: 'No result for "{{query}}"',
	        source: {data: data.customers},
	        callback: {
	            onClickAfter: function (node, a, item, event) {

					$("#SalesOrderClientID").val($customerIds[item.display])
					$("#SalesOrderContactPerson").focus();
	            }
	        },
	        debug: false
	    })
		
		
		
		var data = {
      
	        "products": [<?php echo $product_str; ?>]
	    };

	    $('#q2').typeahead({
	        minLength: 1,
	        maxItem: 15,
	        order: "asc",
	        hint: true,
	        backdrop: {
	            "background-color": "#fff"
	        },
	        emptyTemplate: 'No result for "{{query}}"',
	        source: {data: data.products},
	        callback: {
	            onClickAfter: function (node, a, item, event) {
					$("#product_id").val($productIds[item.display]);
					$("#newqty").val("1");
					$("#newprice").val($productPrices[item.display]);
					$("#newqty").focus();
	            }
	        },
	        debug: false
	    })
		
		$( "#addnewproduct" ).submit(function( event ) {
			
			  event.preventDefault();
			  var str = "";
			  str += "<tr class='product_" + $("#product_id").val() + "'>";
			  str += "<td>" + $("#q2").val() + "</td>";
			  str += "<td class='prod_qty''>" + $("#newqty").val() + "</td>";
			  str += "<td>" + $("#newprice").val() + "</td>";
			  str += "<td class='subtotal'>" + ($("#newprice").val() * $("#newqty").val()).toFixed(2) + "</td>";
			  str += "<td>";
			  str += "<input type='hidden' name='products[" +$("#product_id").val() + "][sku]' value='" +  $productSkus[$("#q2").val()] + "' />";
			  str += "<input type='hidden' name='products[" +$("#product_id").val() + "][title]' value='" +  $("#q2").val() + "' />";
			  str += "<input type='hidden' name='products[" +$("#product_id").val() + "][qty]' value='" +   $("#newqty").val() + "' />";
			  str += "<input type='hidden' name='products[" +$("#product_id").val() + "][price]' value='" +  $("#newprice").val() + "' />";
			  str += "<input type='hidden' name='products[" +$("#product_id").val() + "][subtotal]' value='" +  ($("#newprice").val() * $("#newqty").val()).toFixed(2) + "' />";
			  
			  str += "<a href='javascript:;' class='remove_product' title='.product_" + $("#product_id").val() + "'>X</a>";
			  str +="</td>";
			  str += "</tr>";
		  
			 $("#product_table tbody").append(str);
			 $("#q2").val("");
			 $("#newqty").val("");
			 $("#newprice").val("");
			 $("#q2").focus();
			 computePrice();
		});
		
	
		 $( '#product_table tbody' ).on( 'click', 'a', function () {
			  if (confirm("Are you sure you want to remove this item?")) {
 				  $($(this).attr("title")).remove();
				  computePrice();
			  }
		 });
		 $( "#SalesOrderContactPerson").change(function() {
			$( "#SalesOrderContactPerson_2").val($(this).val());
		 });
		 
		 $( "#SalesOrderInventoryLocationID").change(function() {
			$( "#SalesOrderInventoryLocationID_2").val($(this).val());
		 });
		 

		 $("#newqty").focus(function() { $(this).select(); } );
	
	});

</script>
	
</body>
</html>