<?php
	session_start();
	if(!isset($_SESSION['user'])){
		header("location:login.php");
	}
	include "connect.php";
	$username=$_SESSION['user'];
	$sql = "select id from user where username='$username'";
	$result= mysqli_query($conn,$sql);
	$user = mysqli_fetch_array($result);
	$id= $user['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Trang chủ</title>
	<link rel="stylesheet" href="css/search.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
	<div>
	<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <p class="navbar-brand" href="#">Hello <?php echo $username; ?></p>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="index.php">Home</a></li>     
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="update.php"><span class="glyphicon glyphicon-user"></span>Sửa thông tin</a></li>
      <li><a href="logout.php"><span class="glyphicon glyphicon-log-in"></span>Đăng xuất</a></li>
    </ul>
  </div>
</nav>
<!-- tìm kiếm -->
<div class="search-container">
	<form action="" method="POST">
		<input type="text" name="search" placeholder="Tìm kiếm...">
		<button type="submit" name="submit">Tìm kiếm</button>
	</form>
</div>
</div>
	<?php 
			include 'connect.php';
			if(isset($_POST['submit'])){
				$search = preg_replace("/[^a-zA-Z0-9]+/", "", $_POST['search']);
				// $search= $_POST['search'];
				$sql="select * from user where username like '%$search%' ";
				$result= mysqli_query($conn,$sql);
				if(mysqli_num_rows($result)==0){
					die("Không tìm thấy user");
				}else{
					while ($user= mysqli_fetch_array($result)) {
					?>
						<div id="username"> <a href="user.php?username=<?php echo $user['username']; ?>"> <?php echo $user['username']; ?> </a> <br> </div>
						<?php
				}
				}
				
			}		
	 ?>
</body>
</html>