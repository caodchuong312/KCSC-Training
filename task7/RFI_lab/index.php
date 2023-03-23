<form action="" method="get">
    <input type="text" name="page" placeholder="enter your page...">
    <input type="submit" value="Submit">
</form>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_GET['page'])){
    $page = $_GET['page'];
    include($page);
}
?>
