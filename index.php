<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>The Wall</title>
	<link rel="stylesheet" href="process.php">
	<style type="text/css">
	*{
		font-family: sans-serif;
	}
	label{
		display: block;
	}
	fieldset{
		width: 35em;
	}
	body{
		background-color: pink;
		display: inline-block;
	}
	.danger{
		display: block;
		color: yellow;
	}
	.success{
		display: block;
		color: green;
	}
	</style>
</head>
<body>
<?php 
	if(isset($_SESSION['errors']))
{
	foreach($_SESSION['errors'] AS $error)
	{
?>
		<span class="danger"><?= $error ?></span>
<?php 
	}
	unset($_SESSION['errors']);
}

	if(isset($_SESSION['success']))
	{
?>
		<span class='success'><?= $_SESSION['success'] ?></span>
<?php		
//		unset($_SESSION['success'])
	}
?>
<form method="post" action="process.php">
<fieldset>
	<legend>Registration</legend>

	<label>First Name: <input type="text" name="first_name"></label>
	<label>Last Name: <input type="text" name="last_name"></label>
	<label>Email: <input type="text" name="email"></label>
	<label>Password: <input type="text" name="password"></label>
	<label>Confirm Password: <input type="text" name="passconf"></label>
	<input type="hidden" name="action" value="register">
	<input type="submit" value="Register">
</fieldset>
</form>

<form method="post" action="process.php">
<fieldset>
	<legend>Login</legend>
	<label>Email: <input type="text" name="email"></label>
	<label>Password: <input type="text" name="password"></label>
	<input type="hidden" name="action" value="login">
	<input type="submit" value="Login">
</fieldset>
</form>
</body>
</html>