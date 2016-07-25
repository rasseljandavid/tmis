<?php
	//All functions are ordered alphabetically and named as verb

	session_start();

	function back() {
		$back = $_SERVER['HTTP_REFERER'];
		header("Location: $back");
	}
	
	function checkPage($requestUri) {
		$urlArr = explode(",", $requestUri);
	
	    $current_file_name = basename($_SERVER['PHP_SELF'], ".php");
  
	    if (in_array($current_file_name, $urlArr)) {
	        return 'active';
		}
	}

	function eDebug($data, $stop = false) {
		
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		if($stop == true) {
			exit();
			die();
		}
	}
	
	function formatToAmount($num, $symbol = "P", $decimal = 2) {
		
		if($num == null) {
			return;
		}
		
		if($symbol == null) {
			return round($num, $decimal);
		}
		return($symbol . " " . number_format($num, $decimal));
	}
	
	function formatToPercent($percent) {
		$rate = round($percent * 100);
		
		return $rate . "%";
	}
	
	function getInterestRates() {
		$interestRates = array("0.0", ".07", "0.1");
		
		return $interestRates;
	}
	
	function getMartialStatus() {
		$status = array (
				"Single","Married","Separated","Widowed"
			);
			
		return $status;
	}
	
	function getMonths() {
		$months = array (
				"January","February","March","April","May","June",
				"July","August","September","October","November","December"
			);
			
		return $months;
	}
	
	function getNumberOfMonths() {
		$months = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
		
		return $months;
	}
	
	function getYears() {
		$years = array (
				"2013", "2014", "2015", "2016", "2017", "2018", "2019", "2020"
			);
			
		return $years;
	}
	
	function paginate($query, $per_page = 10,$page = 1, $url = '?', $total_row = 0) {        
    	$query = "SELECT COUNT(*) as `num` FROM {$query}";
		if($total_row > 0 ) {
			$total =  $total_row;
		} else {
			$row = mysql_fetch_array(mysql_query($query));
			$total = $row['num'];
		}
		
        $adjacents = "2"; 

    	$page = ($page == 0 ? 1 : $page);  
    	$start = ($page - 1) * $per_page;								
		
    	$prev = $page - 1;							
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;
    	
    	$pagination = "";
    	if($lastpage > 1)
    	{	
    		$pagination .= "<div class='pagination'><ul>";
                   // $pagination .= "<li class='details'>Page $page of $lastpage</li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{	
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li class='active'><a>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))		
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='active'><a>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}

    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    				
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='active'><a>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    			
    				$pagination.= "<li><a href='{$url}page=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}page=$lastpage'>$lastpage</a></li>";		
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}page=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}page=2'>2</a></li>";
    		
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li class='active'><a>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}page=$counter'>$counter</a></li>";					
    				}
    			}
    		}
    		
    		if ($page < $counter - 1){ 
    			$pagination.= "<li><a href='{$url}page=$next'>Next</a></li>";
                $pagination.= "<li><a href='{$url}page=$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li class='active'><a>Next</a></li>";
                $pagination.= "<li class='active'><a>Last</a></li>";
            }
    		$pagination.= "</ul></div>\n";		
    	}
    
    
        return $pagination;
    }

	function showAccordion($latest_date, $earliest_date) {
		
		$latest_year    = date("Y", strtotime($latest_date));
		$earliest_year  = date("Y", strtotime($earliest_date));
		$current_month  = date("F");

		for($i = $earliest_year; $i <= $latest_year; $i++) {
			$years[] = $i;
		}

		$months = getMonths();

		$accordion_data = array();
		$i=0;
		foreach($years as $year) {
			$accordion_data[$i]->year = $year;

			foreach($months as $month) {

				$accordion_data[$i]->months[] = $month;

				if($year == $latest_year && $month == $current_month) {
					break;
				}
			}

			$accordion_data[$i]->months = array_reverse($accordion_data[$i]->months);
			$i++;
		}
		
		return array_reverse($accordion_data);
	}

	function showAlerts($alerts) {
		$str = "";
		
		if(!empty($alerts)) {
			foreach($alerts as $alert) {
				if(empty($alert->type)) {
					$alert->type = "error";
				}
				$str .= "<div class='alert alert-{$alert->type}'>";
				$str .= "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
				$str .= "<p>{$alert->msg}</p>";
				$str .= "</div>";
			}
		}
		
		if(!empty($_SESSION['ALERT'])) {
			foreach($_SESSION['ALERT'] as $alert) {
				if(empty($alert->type)) {
					$alert->type = "error";
				}
				$str .= "<div class='alert alert-{$alert->type}'>";
				$str .= "<button type='button' class='close' data-dismiss='alert'>&times;</button>";
				$str .= "<p>{$alert->msg}</p>";
				$str .= "</div>";
			}

			unset($_SESSION['ALERT']);
		}
		
		return $str;
	}
	
	function showBreadcrumbs($current_file_name, $client_id = null) {
		
		$homelink = "/plutus";
		$breadcrumbs = array();
		$breadcrumbs['index'] 	 = array("Home" => "");
		$breadcrumbs['account'] = array("Home" => $homelink, "Account" => "");
		$breadcrumbs['payments'] = array("Home" => $homelink, "Collections" => "");
		$breadcrumbs['createBatchPayment'] = array("Home" => $homelink, "Account" => "payments.php", "Batch Payment" => "");
		$breadcrumbs['loans'] = array("Home" => $homelink, "Loans" => "");
		$breadcrumbs['clients'] = array("Home" => $homelink, "Accounts" => "");
		$breadcrumbs['client'] = array("Home" => $homelink, "Accounts" => "clients.php", "Account Details" => "");
		$breadcrumbs['sortClients'] = array("Home" => $homelink, "Accounts" => "clients.php", "Sort Clients" => "");
		$breadcrumbs['report'] = array("Home" => $homelink, "Report" => "");
		if(!empty($breadcrumbs[$current_file_name])) {
			$str = "<ul class='breadcrumb'>";
			foreach($breadcrumbs[$current_file_name] as $key => $value) {
				if($value == "") {
					$str .= "<li class='active'>{$key}</li>";
				} else {
					$str .= "<li><a href='{$value}'>{$key}</a> <span class='divider'>/</span></li>";
				}
			}
			
			$str .= "</ul>";
		}
		
		return $str;
	}
	
	require("database.php");

?>