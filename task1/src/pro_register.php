<?php 
	include 'connect.php';
	$username=$_POST['username'];
	$password=$_POST['password'];
	$sql="select * from user where username='$username'" ;
	$result= mysqli_query($conn,$sql);
	if(mysqli_num_rows($result)>0){
		$error="Tên đăng nhập đã tồn tại";
		header("location:register.php?error=$error");
		exit;
	}
	$password=md5($password);
	$sql="insert into user(username,password) values ('$username','$password')";
	$result= mysqli_query($conn,$sql);
	header("location:login.php?mess=Đăng ký thành công");


	