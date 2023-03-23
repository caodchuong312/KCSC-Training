<a href="?page=file.php">file</a>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$file = $_GET[ 'page' ];
$file = str_replace( array( "../", "..\\" ), "", $file ); 
include($file);
?>
