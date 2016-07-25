<?php

$file = isset($_FILES['uploadFile']['name']) ? $_FILES['uploadFile'] : '';

$fileArr = explode("." , $file["name"]);

$ext = $fileArr[count($fileArr)-1];


$allowed = array("jpg", "jpeg", "png", "gif", "bmp");

$filename = "tmpPictureName_" . time() . "." . $ext;

if (in_array(strtolower($ext), $allowed)){
    move_uploaded_file($file["tmp_name"],"site/clients/".$filename);
    echo $filename;
} else {
    echo "invalid";
}

?>