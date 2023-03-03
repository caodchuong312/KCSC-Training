<?php 
require "config.php";

echo "<h1>The Peopleless Circus</h1>";
echo "<p>When the leaflet dropped through my letterbox I thought it was a joke. The Peopleless Circus was in town. At the risk of sounding like a negative Nancy, I couldn't help thinking what is the world coming to. I'm not keen on all these plans for driverless, or driver assisted, vehicles mostly from a safety aspect. But what on earth would a peopleless circus consist of? Of course, I had to go, curiosity killed the cat and all that.</p>";
echo "<h2>Comments</h2>";

$sql="SELECT * from blog";
$result = mysqli_query($conn,$sql);
if(mysqli_fetch_assoc($result)){
	while ($row = mysqli_fetch_assoc($result)) {
		echo  $row['name']. ": ";
		echo $row['comment'];
		echo "<br>";
		}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Stored XSS</title>
	<style>
		body{
			font-family: "Courier New", Courier, monospace;
		}
	</style>
</head>
<body>
	<h3>Write your comment!</h3>
	<form action="" method="POST">
		<label>Comment:</label><br>
		<textarea name="comment"></textarea> <br>
		<label>Name:</label><br>
		<input type="text" name="name"><br>
		<input type="submit" value="Post comment">
	</form>
</body>
</html>
<?php 
if(isset($_POST['comment'])&&isset($_POST['name'])){
	$comment=$_POST['comment'];
	$name=$_POST['name'];
	$sql="insert into blog (comment, name) VALUES ('$comment', '$name')";
	$result = mysqli_query($conn,$sql);
	if ($result) {
		header("location:redirect.php");
	}
}