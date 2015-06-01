<?php
require_once('connection.php');
?>

<html>
<head>
	<title>comments page</title>
</head>
<body>
<h2>Coding Dojo Wall</h2>

<form method="post" action="process.php">
<input type="hidden" name="action" value="logout">
<input type="submit" value="Log Out">
</form>
</body>
</html>