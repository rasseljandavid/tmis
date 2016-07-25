<?php
	session_start();
        error_reporting(0);
	$configs = parse_ini_file("site/config.ini");
	if(empty($configs['client_image_directory'])) {
		$configs['client_image_directory'] = "site/clients/";
	}
	date_default_timezone_set('Asia/Manila');

	include('lib/mysql.php');
	
	$db = new mysql_database($configs['dbusername'], $configs['dbpassword'], $configs['dbserver'], $configs['dbdatabase']);
	
	//Change the timezone to be asia manila
	date_default_timezone_set('Asia/Manila');
	$db->sql("SET `time_zone` = '".date('P')."'");
	
	$connect = mysql_connect($configs['dbserver'], $configs['dbusername'], $configs['dbpassword']);
	mysql_select_db($configs['dbdatabase']);
	
	include('lib/functions.php');
	
	if(!isset($_SESSION['ID']) && substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1) != "login.php" && !isset($_SESSION['COOKIE'])) {
	
		$hasCookie = CheckCookieLogin();
	
		if(!$hasCookie) {
			header("location: login.php");
			exit();
		}
	}

?>