<?php
include("simple_html_dom.php");

// Create DOM from URL or file
$html = file_get_html('http://192.168.1.130/analog');

// Find all images 
foreach($html->find('table') as $element) {
    echo "<pre>";
	print_r($element);
	echo "</pre>";
}

?>

