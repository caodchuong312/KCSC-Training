<?php 
	session_start();
	include 'connect.php';
	$username=$_POST['username'];
	$password=$_POST['password'];
	$password=md5($password);
	$sql="select * from user where username='$username' and password='$password'";
	$result=mysqli_query($conn,$sql);	
	if (mysqli_num_rows($result)==0) {
		die("Thông tin không chính xác");	
	}else{
		$user = mysqli_fetch_array($result);
		$id = $user['id'];
		echo "Đăng nhập thành công";
		$_SESSION['user']= $username;	
		$_SESSION['id']= $id;
		header("location:index.php");
	}
