<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>login</title>
</head>
<body>
	<form action="" method="POST">
		<span>Username: </span>
		<br>
		<input type="text" name="username">
		<br>
		<span>Password: </span>
		<br>
		<input type="password" name="password">
		<br>
		<input type="submit" value="Submit">
	</form>
</body>
</html>
<?php 
include 'config.php';
if(isset($_POST['username']) || isset($_POST['password']) ){
	$username=$_POST['username'];
	$password=$_POST['password'];
	$stmt = $conn->prepare("SELECT * FROM users where username=? and password=? ");  
	$stmt->bindParam(':username', $username);
	$stmt->bindParam(':password', $password);
	$stmt->execute([$username,$password]);
	$result = $stmt->fetchAll();
	if($result){
		header("location:index.php");
	}
	else{
		echo "incorrect";
	}
}
