<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<form action="" method="POST">
		<label>username</label><br>
		<input type="text" name="username" id="username"><br>
		<label>password</label><br>
		<input type="password" name="password" id="password"><br>
		<input type="submit" value="login">
	</form>
</body>
</html>
<?php 
require 'config.php';
if(isset($_POST['username']) && isset($_POST['password'])){
	error_reporting(0);
	$username= $_POST['username'];
	$password= $_POST['password'];
	if (preg_match('/union|length|substr|from/i', $username))
        echo $username;
    if (preg_match('/union|length|substr|from/i', $password))
        echo $username;
	$sql="select * from users where username= '$username' and password='$password'";
	$result=mysqli_query($conn,$sql);
	$row= mysqli_fetch_array($result);
	echo $username;
	
}