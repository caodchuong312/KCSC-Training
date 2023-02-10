<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Thông tin</title>
	<style>
		label {
  font-weight: bold;
  font-size: 18px;
  margin-right: 10px;
  display: inline-block;
  width: 150px;
  text-align: right;
}
</style>
</head>
<body>
	<?php 
		include 'connect.php';
		if(!isset($_GET['username'])){
			die("Không tìm thấy user");
		}
		$username = preg_replace("/[^a-zA-Z0-9]+/", "", $_GET['username']);
		$sql="select * from user where username = '$username'";
		$result= mysqli_query($conn,$sql);
		if(mysqli_num_rows($result)==0){
			die("Không tìm thấy user");
		}
		$user= mysqli_fetch_array($result);
	 ?>
	 <label>Username: </label>
	 <?php echo $user['username'] ?>
	 <br>
	 <label>Email: </label>
	 <?php if($user['email']==0){echo "Chưa cập nhật";} else{echo $user['email'];}?>
	 <br>
	 <label>Địa chỉ: </label>
	 <?php if($user['address']==0){echo "Chưa cập nhật";} else{echo $user['address'];}?>
	 <br>
	 <label>Số điện thoại: </label>
	 <?php if($user['phone_number']==0){echo "Chưa cập nhật";} else{echo $user['phone_number'];} ?>
	 <br>
	 <label>Giới tính: </label>
	 <?php if($user['gender']==0){echo "Chưa cập nhật";} else{echo $user['gender'];} ?>
	 <br>
	 <a href="index.php">Về Trang chủ</a>
</body>
</html>