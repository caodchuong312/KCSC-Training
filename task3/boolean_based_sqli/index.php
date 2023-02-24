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
	$username= $_POST['username'];
	$password= $_POST['password'];
	$sql="select * from users where username= '$username' and password='$password'";
	$result=mysqli_query($conn,$sql);	
	if($result){
			if (mysqli_num_rows($result)==0) {
				die("incorrect!");	
			}else{
			$user = mysqli_fetch_array($result);
				echo "correct!!!";		
		}			
	}else{
		die("incorrect!");	
	}
	
}
