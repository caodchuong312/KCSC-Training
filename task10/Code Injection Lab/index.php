<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Code Injection Lab</title>
</head>
<body>
	<form action='index.php' method='get'>
        <input type='text' id='input' name='input' />
        <input type='submit' />
        <?php
if (isset($_GET['input'])) {
	if (preg_match('/[a-zA-Z`]/', $_GET['input'])) {
		echo "<p>Hack Detected</p>";
	} else {
		print('<h3>result</h3>');
    eval('print ' . $_GET['input'] . ";");
	}
}
?>
    </form>
</body>
</html>
