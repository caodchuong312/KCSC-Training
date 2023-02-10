<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Đăng nhập</title>
	<script type="text/javascript" src="js/validate.js"></script>
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
	<?php 
		if(isset($_GET['mess'])){
			$mess=$_GET['mess'];
			echo $mess;	
		}	
	?>
	<h1 style="text-align: center;">Đăng nhập</h1>
	<form id="form-login" action="pro_login.php" method="POST" onsubmit="return validate(event)">
    <label>Tên đăng nhập:</label>
    <input type="text" id="username" name="username">
    <label>Mật khẩu:</label>
    <input type="password" id="password" name="password">
	<input type="submit" value="Đăng nhập">
	<br>
	<span style="color: red;" id="error"></span>
	<br>
	<a href="register.php" >Đăng Ký</a>
</form>
</body>
</html>