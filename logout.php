<?php
if(isset($_COOKIE['uname'])) {
	unset($_COOKIE['uname']);
	setcookie("uname", "", time()-3600, '/');
}
// If you are using session_name("something"), don't forget it now!
session_start();

// Unset all of the session variables.
$_SESSION = array();

session_destroy();

header("location: login.php");

?>