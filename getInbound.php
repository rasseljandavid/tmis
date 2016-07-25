<?php
$suppliers = getSuppliers();

$data = array();
foreach($suppliers as $supplier) {

	
	$arr = "";
	$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
	//$arr['mvDocumentStatus'] = "Closed";
	$arr['mvDocumentTypeAbbreviation'] = "GIPI";
	$arr['query'] = "mv.DocumentSupplierClientID={$supplier->SupplierClientID}";
	
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
	
	foreach($res->mvDocuments as $item) {
		if(trim($item->DocumentTypeAbbreviation) == "GIPI") {
		
			
if($item->DocumentStatus == "Verified" || $item->DocumentStatus=="Closed") {
			@$data[$supplier->SupplierClientName][convertDate($item->DocumentDate)]->total += $item->DocumentAmountGrandTotal;
			$data[$supplier->SupplierClientName][convertDate($item->DocumentDate)]->color  = $supplier->SupplierClientComments;
}
		}
	}
	
}
	
	function getSuppliers() {
		$arr = "";
		$arr['APIKEY'] = "c8c3ec7f1b65dc9d@m11394";	
		$arr['query'] = "mv.SupplierClientType = 1";
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
	
	
	$dt = array();
	$i = 0;
	foreach($data as $key => $item) {
		foreach($item as $key2 => $item2) {
			
			
			$tit = explode(" / ", $key);
		@$dt[$i]['start'] = $key2;
		@$dt[$i]['title'] = $tit[0] . " \n P" . number_format($item2->total, 2);
		$dt[$i]['className'] = $item2->color;
		 
			
			$i++;
		}
	}
	$json_data = json_encode($dt);
	

	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='css/fullcalendar.css' rel='stylesheet' />
<script src='lib/moment.min.js'></script>
<script src='lib/jquery.min.js'></script>
<script src='js/fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {

		$('#calendar').fullCalendar({
			header: {
							left: 'prev,next today',
							center: 'title',
							right: 'month,basicWeek,basicDay'
						},
			editable: true,
			events: <?php echo $json_data; ?>
		});
		
	});

</script>
<style type="text/css">
<?php foreach($suppliers as $supplier)  : ?>

.<?php echo $supplier->SupplierClientComments; ?> { background-color: <?php echo $supplier->SupplierClientComments; ?>;}

<?php endforeach; ?>

.yellow, .LightCyan, .Beige { color: #000; }
	body {
		margin: 40px 10px;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>
</head>
<body>

	<div id='calendar'></div>

</body>
</html>
