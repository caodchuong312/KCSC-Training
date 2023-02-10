<?php 
$search = $_GET['search'];
include 'connect.php';
$sql="select * from user where username like '%$search%";
$result= mysqli_query($conn,$sql);
