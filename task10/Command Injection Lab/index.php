<?php
if (isset($_GET['submit'])&&isset($_GET['ip'])) {
	if(preg_match('/ls|cat|echo|id|bash|curl|sh|\(|\)|;|\&|\'|\"/i',$_GET['ip'])){
		die("no");
	}else{
		$ip=$_GET['ip'];
	}
	$result=system("ping -c 3 $ip");
	if ($result) {
		echo "<p>Host $ip is reachable</p>";
	} else {
		echo "<p>Host $ip is not reachable</p>";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ping Tool</title>
</head>
<body>
    <form method="get">
        <label for="ip">IP Address:</label>
        <input type="text" id="ip" name="ip" placeholder="127.0.0.1" >
        <input type="submit" name="submit" value="Ping">
    </form>
</body>
</html>
