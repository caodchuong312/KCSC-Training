<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Đăng ký</title>
	<script type="text/javascript" src="js/validate.js"></script>
	<link rel="stylesheet" href="css/register.css">
</head>
<body>
	<?php if (isset($_GET['error'])) { ?>
      <p style="color: red; text-align: center;"><?php echo $_GET['error']; ?></p>
    <?php } ?>
	<h1 style="text-align: center;">Đăng ký</h1>
	<form name="form-register" action="pro_register.php" method="POST" onsubmit="return validate(event)">
		<label>Tên đăng nhập</label>
		<input type="text" id="username" name="username" placeholder="Tên đăng nhập" > 
		<label>Mật khẩu</label>
		<input type="password" id="password" name="password" placeholder="Mật khẩu" >
		<label>Nhập lại mật khẩu</label>
		<input type="password" id="re_password" name="re_password" placeholder="Nhập lại mật khẩu" >
		<input type="submit" value="Đăng ký">
		<br>
		<span style="color: red;" id="error"></span>
		<br>
		<a href="login.php" >Đăng nhập</a>

	</form>
</body>
</html>
