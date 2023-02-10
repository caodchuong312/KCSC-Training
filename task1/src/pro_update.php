<?php 
	$id=$_POST['id'];
	$email=$_POST['email'];
	$address=$_POST['address'];
	$phone_number=$_POST['phone_number'];
	$gender = $_POST['gender'];
	include 'connect.php';
	$sql="update user
	set
	email='$email',
	address='$address',
	phone_number='$phone_number',
	gender='$gender'	
	where
	id = '$id'";
	mysqli_query($conn,$sql);
	mysqli_close($conn);
	echo "Cập nhật thành công!";
 ?>
 <br>
 <a href="update.php">Quay lại</a>