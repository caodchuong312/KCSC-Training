<?php 
$name = isset($_GET['name']) ? $_GET['name'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Refected XSS</title>
</head>
<body>
    <form action="" method="GET">
        <label>Enter your name:</label> <br>
        <input type="text" name="name">
        <input type="submit" value="Submit">
    </form>
    <p>Hello <?=$name?>!</p>
</body>
</html>
