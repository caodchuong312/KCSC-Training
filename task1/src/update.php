<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thông tin</title>
	<link rel="stylesheet" href="css/update.css">
	<script type="text/javascript" src="js/validate.js"></script>
</head>
<body>
	<?php
		session_start();
		$id = $_SESSION['id'];
		include 'connect.php';
		$sql = "select * from user where id = $id";
		$result = mysqli_query($conn,$sql);
		$user = mysqli_fetch_array($result);
	 ?>	
		<h2 style="text-align: center;">Sửa thông tin</h2>
		<form action="pro_update.php" method="post" id="form-update">
			<input type="number" name="id" hidden value="<?php echo $id;?>">
			<label>Username: <?php echo $user['username']; ?></label> <br>
			<label>Email</label>
			<input type="email" name="email" value="<?php if($user['email']==0 ){echo "";}else{echo $user['email'];}?>">
			<label>Địa chỉ</label>
			<input type="text" name="address" value="<?php if($user['address']==0 ){echo "";}else{echo $user['address'];}?>">
			<label>Số điện thoại</label>
			<input type="tel" name="phone_number" value="<?php if($user['phone_number']==0 ){echo "";}else{echo $user['phone_number'];}?>">
			<label>Giới tính</label>
				<input type="radio" id="male" name="gender" value="Nam">
				<label for="male">Nam</label>
				<input type="radio" id="female" name="gender" value="Nữ">
				<label for="female">Nữ</label>
				<input type="radio" id="other" name="gender" value="Khác">
				<label for="other">Khác</label>
			<input type="submit" value="Cập nhật">
		</form>
			<br>
	 		<a href="index.php">Về Trang chủ</a>

		<script type="text/javascript">
			// tự checked giới tính sau khi update
			var savedGender = "<?php echo $user['gender']; ?>";
			if(savedGender == "Nam") {
				document.getElementById("male").checked = true;
			}else if(savedGender == "Nữ") {
				document.getElementById("female").checked = true;
			}else if(savedGender == "Khác") {
				document.getElementById("other").checked = true;
			}
		</script>
</body>
</html>