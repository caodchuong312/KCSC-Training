<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="">
	<style>
		*{
			font-family: Arial, Helvetica, sans-serif;
		}
		table {
		  border: 1px solid black;
		  border-collapse: collapse;
		  padding: 5px;
		}

		th {
		  background-color: lightgrey;
		}

		td, th {
		  border: 1px solid black;
		  padding: 5px;
		}
		.des{
			width: 500px;
		}
		a:link, a:visited {
		  background-color: white;
		  color: black;
		  border: 2px solid green;
		  padding: 10px 20px;
		  text-align: center;
		  text-decoration: none;
		  display: inline-block;
		}

		a:hover, a:active {
			background-color: green;
	  		color: white;
		}

	</style>
</head>
<body>
	<a href="?type=iphone">iphone</a>
	<a href="?type=samsung">samsung</a>
	<a href="?type=xiaomi">xiaomi</a>
</body>
</html>
<?php 
require "config.php";
if(isset($_GET['type'])){
	$type=$_GET['type'];
	echo "<br>";
	echo "<br>";

	$sql="SELECT * from products where type= '$type'";
	$result = mysqli_query($conn,$sql);
    	print_r(mysqli_error($conn));
    	if(mysqli_fetch_assoc($result)){
			echo '<table>';
			echo '<tr><th>Name</th><th>Price</th><th></th></tr>';
			while ($row = mysqli_fetch_assoc($result)) {
			echo '<tr>';
			echo '<td>' . $row['name'] . '</td>';
			echo '<td>' . $row['price'] . '</td>';
			echo '<td><a class="detail" href="">Xem chi tiết</a></td>';
			echo '</tr>';
			}
			echo '</table>';			
    	}		
}
