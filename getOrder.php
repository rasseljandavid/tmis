<?php

if(!empty($_POST['submit'])) {
   
    
    $products = array();
    $i = 0;
    foreach($_POST['product'] as $key => $product) {
        if($product['actual_qty'] > 0) {
            @$products[$i]->PurchaseOrderRowProductSKU = $key;
            $products[$i]->PurchaseOrderRowQuantity = $product['actual_qty'];
            $products[$i]->PurchaseOrderRowUnitPriceWithoutTaxOrDiscount = $product['actual_price'];
            // IF Ocampo
            if($_POST['PurchaseOrderSupplierID'] == 102) {
                $products[$i]->PurchaseOrderRowDiscountID = "3";
            }
                
            $i++;
        }
    }
    date_default_timezone_set("Asia/Manila");
    $dt = date("Y-m-d h:i:s");


    $data  = "";
    @$data->PurchaseOrderId = "";
    $data->PurchaseOrderNo = "";
    $data->PurchaseOrderReferenceNo = "";
    $data->PurchaseOrderReferenceApplication = "";
    $data->PurchaseOrderDate = "DATETIME'{$dt}'";
    $data->PurchaseOrderCustomOrderDate1 = "";
    $data->PurchaseOrderCustomOrderDate2 = "";
    $data->PurchaseOrderCurrencyCode     = "PHP";
    $data->PurchaseOrderSupplierID           = $_POST['PurchaseOrderSupplierID'];
    $data->PurchaseOrderAddress     = "";
    $data->PurchaseOrderPickupAddress    = "";
    $data->PurchaseOrderContactPerson      = "";
    $data->PurchaseOrderInventoryLocationID      = "2";
    $data->PurchaseOrderComments      = "";
    $data->PurchaseOrderTags      = "";
    $data->PurchaseOrderAmountSubtotalWithoutTaxAndDiscount      = "0";
    $data->PurchaseOrderAmountTotalTax      = "0";
    $data->PurchaseOrderDetails = $products;
    $data->PurchaseOrderStatus = "Verified";
    


    $arr = "";
    $arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
    $arr['mvPurchaseOrder']  = $data;
    $arr['mvRecordAction']  = "Insert";

    $str = json_encode($arr);


    $ch = curl_init();
    $headers = array('Accept: application/json','Content-Type: application/json'); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_URL,"http://apitest.megaventory.com/json/reply/PurchaseOrderUpdate?format=json");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);

    $res = json_decode($server_output);

  
    curl_close ($ch);
    header("location: getOrder.php");
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
<?php

function getWholeOrders($qty, $perbox) {
	if($perbox != 0) {
	$x = $qty / $perbox;
	$temp = explode(".", $x);
	$temp[1] = @substr($temp[1], 0, 1);
	if($temp[1] >= 5) {
		return (int) $temp[0] + 1;
	} else {
		return (int) $temp[0]; 
	}
	}
}



//https://api.megaventory.com/v2/json/reply/documentGet?APIKEY=3b8db5d01e61d276@m513&mvDocumentStatus=verified&query=
//https://api.megaventory.com/v2/json/reply/documentGet?APIKEY=YOUR_API_KEY_HERE&mvDocumentStatus=verified

//Get all the suppliers
//https://api.megaventory.com/v2/json/reply/SupplierClientGet?APIKEY=YOURAPIKEYHERE&query=mv.SupplierClientName like '%mag%' AND  mv.SupplierClientType = 1

$arr2 = "";
$arr2['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
$arr2['query'] = "mv.SupplierClientType = 1";
$str2 = json_encode($arr2);

$ch2 = curl_init();
$headers2= array('Accept: application/json','Content-Type: application/json'); 
curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);
curl_setopt($ch2, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch2, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/SupplierClientGet?format=json");
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS,$str2);

// receive server response ...
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

$server_output2 = curl_exec ($ch2);

$res2 = json_decode($server_output2);



curl_close ($ch2);
$suppliers = array();
foreach($res2->mvSupplierClients as $supplier) {

	$suppliers[$supplier->SupplierClientID] = $supplier->SupplierClientName;
}




$arr2 = "";
$arr2['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
$arr2['ProductSKU'] = "";
$str2 = json_encode($arr2);



$ch2 = curl_init();
$headers2= array('Accept: application/json','Content-Type: application/json'); 
curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers2);
curl_setopt($ch2, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch2, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/ProductGet?format=json");
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS,$str2);


// receive server response ...
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);

$server_output2 = curl_exec ($ch2);

$res2 = json_decode($server_output2);

curl_close ($ch2);
$products = array();



foreach($res2->mvProducts as $item) {
	@$products[$item->ProductID]->title = $item->ProductDescription;
	$products[$item->ProductID]->supplier = $item->ProductMainSupplierID;
	$products[$item->ProductID]->price = $item->ProductMainSupplierPrice;
	$products[$item->ProductID]->category = $item->ProductCategoryID;
	$products[$item->ProductID]->position = $item->ProductEAN;
	$products[$item->ProductID]->sku = $item->ProductSKU;
	
}

$arr = "";
$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
$arr['ProductSKU'] = "";	
	
$str = json_encode($arr);



$ch = curl_init();
$headers= array('Accept: application/json','Content-Type: application/json'); 
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_URL,"http://api.megaventory.com//v2/json/reply/InventoryLocationStockGet?format=json");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$str);


// receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);

curl_close ($ch);

$res = json_decode($server_output);


foreach($res->mvProductStockList as $item) {
   
	$dt = "";
	$dt[] = @$products[$item->productID]->title;
	$dt[] = ($item->StockPhysicalTotal + $item->StockNonReceivedPOsTotal) - $item->StockNonShippedTotal;
	$dt[] = $item->StockAlertLevelTotal;
	$dt[] = $products[$item->productID]->price;
	$dt[] = $products[$item->productID]->position;

	$dt[] = $products[$item->productID]->position;

	$dt[] = $products[$item->productID]->sku;
	$data[$suppliers[$products[$item->productID]->supplier] . "_".$products[$item->productID]->supplier][$products[$item->productID]->category][] = $dt;
	
  
	
}

foreach($data as $key => $item) {
	foreach($item as $item2) {
		foreach($item2 as $item3) {
	
		
		$myData[$key][] = $item3;
		}
	}
}


foreach($myData as $key => $myArray) {
	
for($i=0; $i< count($myArray); $i++) {
	$temp = explode("/", $myArray[$i][0]);
	$myArray[$i][4] = $temp[count($temp) - 1];

}

$orders = array();

$totalCase = 0;
$total = 0;
foreach($myArray as $item) {

	//If the inventory is less than the alert level
	$item[1] = str_replace(",","",$item[1]);
	$item[2] = str_replace(",","",$item[2]);
	if($item[1] < $item[2]) {
		$qty = $item[2] - $item[1];
		
		if($item[0] <> "") {
			$numOfBox = getWholeOrders($qty, $item[4]);
		
			if($numOfBox > 0) {
			$order['product'] = $item[0];
            $order['product_sku'] = $item[6];
			$order['qty'] = $numOfBox;
            $order['price'] = $item[3]* $item[4];
            $order['actual_price'] = $item[3];
            $order['actual_qty'] = $item[4] * $numOfBox;
            $order['qty_per_case'] = $item[4];
			$order['subtotal'] = $item[3] * $item[4] * $numOfBox;
			$order['position'] = $item[5];
			$orders[] = $order;
			$totalCase += $numOfBox;
			$total += $item[3] * $item[4] * $numOfBox;
	
	
			}
		}
	}


}


	if($totalCase != 0) : ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <?php
            $key_arr = explode("_", $key);
            ?>
            <input type="hidden" name="PurchaseOrderSupplierID" value="<?php echo $key_arr[1]; ?>" />
    		<table id="<?php echo str_replace("/", "",str_replace(" ", "_", $key_arr[0])); ?>" class='table table-striped table-bordered purchase_order_table' style="width: 50%; margin: auto; margin-bottom: 20px;">
    			<thead>
    				<tr>
    					<th colspan="4">
                           
    						<?php echo $key_arr[0]; ?>
						
					
    						<a title="<?php echo str_replace("/", "",str_replace(" ", "_", $key_arr[0]));; ?>" href="javascript:;" class="fa fa-print printthis hidden-print" style="float: right;">&nbsp;</a>
    					</th>
    				</tr>
                
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Qty per Case</th>
                        <th>Total</th>
                    </tr>
    			</thead>
            
			
    			<tbody>
    				<?php foreach($orders as $item) :  ?>
				
    				<tr>
    					<td><?php echo $item['product']; ?>
                            <input type="hidden" class="qty_per_case" value="<?php echo $item['qty_per_case']; ?>" />
                            <input type="hidden" name="product[<?php echo $item['product_sku']; ?>][actual_price]" class="actual_price" value="<?php echo $item['actual_price']; ?>" />
                        </td>
    					<td><?php echo $item['position']; ?></td>
    					<td>
                            <input onkeypress='return event.charCode >= 48 && event.charCode <= 57' type="text" name="" id="" class="form-control text-center qty_case" value="<?php echo $item['qty']; ?>" />
                            <input type="hidden"  name="product[<?php echo $item['product_sku']; ?>][actual_qty]" class="actual_qty" value="<?php echo $item['actual_qty']; ?>" />
                        </td>
    					<td>P<span class='subtotal'><?php echo number_format($item['subtotal'], 2); ?></span>
                            <input type="hidden" class="subtotal_amt" value="<?php echo $item['price']; ?>" />
                           </span>
                        </td>
    				</tr>	
    				<?php endforeach; ?>
    			</tbody>
    			<tfoot>
    				<tr>
    					<td colspan="2">Total</td>
                        <td><span class="grandtotalcase"><?php echo $totalCase; ?></span> Cases
                             <input type="hidden" name="PurchaseOrderTotalQuantity" class="grandtotalcase_amt" value="<?php echo $totalCase; ?>" />
                        </td>
    					<td>P<span class="grandtotal"><?php echo number_format($total, 2); ?></span>
                            <input type="hidden" name="PurchaseOrderAmountGrandTotal" class="grandtotal_amt" value="<?php echo number_format($total, 2); ?>" />
                        </td>
    				</tr>
                
    				<tr>
    					<th colspan="4" class="hidden-print">
                            <button value="submit" name="submit" onclick="return confirm('Are you sure you want to submit this purchase order?');" type="submit" class="btn btn-primary center-block">Submit Order</button>
    					</th>
    				</tr>
    			</tfoot>
    		</table>
        </form>
	<?php endif ; 
}


?>

<script type="text/javascript">
	
function formatCurrency(total) {
    var neg = false;
    if(total < 0) {
        neg = true;
        total = Math.abs(total);
    }
    return parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
}
	
	
	$(function () {
		$(".printthis").click(function() {
			$(".table").css( "display", "none" );
			$("#" + $(this).attr("title")).css( "display", "table").css("width", "100%").addClass("table-condensed");
			
			window.print();
			$(".table").css( "display", "table" ).css("width", "50%").removeClass("table-condensed");
		});
        
      
        
        $('.qty_case').on('input',function(e){
            
            if($(this).val() > 0) {
                $(this).closest( "tr" ).removeClass("hidden-print");
            } else {
                $(this).closest( "tr" ).addClass("hidden-print");
            }
            $(this).closest( "tr" ).find(".subtotal").text(formatCurrency($(this).closest( "tr" ).find(".subtotal_amt").val() * $(this).val()));
            
            
             
            
             $(this).closest( "tr" ).find(".actual_qty").val($(this).val() * $(this).closest( "tr" ).find(".qty_per_case").val());
            
            //computer the qty
            var total_qty = 0;
             $(this).closest("table").find('.qty_case').each(function() {
                    // Do your magic here
                   // if (this.value.match(/\D/)) // regular expression for numbers only.
                 if($.isNumeric(this.value)) {
                       var b = parseFloat(this.value);
                       total_qty = total_qty + b
                 }
            });
            $(this).closest( "table" ).find(".grandtotalcase").text(total_qty);
            $(this).closest( "table" ).find(".grandtotalcase_amt").val(total_qty);
            
        //compute the total
            var grandtotal = 0;
            $(this).closest("table").find('.subtotal').each(function(index, elem) {
                    
              var b =  parseFloat($(this).text().replace (/,/g, ""));
              grandtotal = grandtotal + b;
           });
           
           $(this).closest( "table" ).find(".grandtotal").text(formatCurrency(grandtotal));
           $(this).closest( "table" ).find(".grandtotal_amt").val(grandtotal);
            
        });
		

	});
</script>

	
</body>
</html>