<?php
session_start();
require_once("connection.php");

$errors = array();

function alphaOnly($string)
{
	for($i = 0; $i < strlen($string); $i++)
	{
		if(is_numeric($string[$i]))
		{
			return false;
		}
	}
	return true;
}


// registration routine
if(isset($_POST['action']) && $_POST['action'] == "register")
{
	if(strlen($_POST['first_name']) < 1 || !alphaOnly($_POST['first_name']))
	{
		$errors[] = "First name is required / cannot contain numbers.";
	}
	if(strlen($_POST['last_name']) < 1 || !alphaOnly($_POST['last_name']))
	{
		$errors[] = "Last name is required / cannot contain numbers.";
	}
	if(strlen($_POST['email']) < 1)
	{
		$errors[] = "Email address required.";
	}
	else
	{
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$errors[] = "Invalid email address.";
		}

		$esc_email = mysqli_real_escape_string($connection, $_POST['email']);
		$esc_email = strtolower($esc_email);

		$checkForEmail = "SELECT * FROM users WHERE email='{$esc_email}'";

		$user = mysqli_query($connection, $checkForEmail);
		$user = $user->fetch_assoc();

		if(count($user) > 0)
		{
			$errors[] = "An account for that email address already exists.";
		}

	}
	if(strlen($_POST['password']) < 1)
	{
		$errors[] = "Password required.";
	}
	else
	{
		if($_POST['password'] != $_POST['passconf'])
		{
			$errors[] = "Password must match password confirmation field.";
		}
	}

	if(strlen($_POST['passconf']) < 1)
	{
		$errors[] = "Password confirmation required.";
	}

	if(count($errors) > 0)
	{
		$_SESSION['errors'] = $errors;
		header("Location: index.php");
		exit();
	}
	else
	{
		$esc_first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
		$esc_last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
		$ecs_email= mysqli_real_escape_string($connection, $_POST['email']);

		$salt = bin2hex(openssl_random_pseudo_bytes(22));
		$encrypted_password = md5($_POST['password'] . '' . $salt);

		$query = "INSERT INTO users (first_name, last_name, email, password, salt, created_at, updated_at)
					VALUES ('{$esc_first_name}', '{$esc_last_name}', '{$esc_email}', '{$encrypted_password}', '{$salt}', NOW(), NOW())";
		
		if(mysqli_query($connection, $query))
		{
			$_SESSION['success'] = "Successfully Registered! Please login to continue.";
			header("Location: index.php");
			exit();
		}
	}
}

//routine for login
if(isset($_POST['action']) && $_POST['action'] == "login")
{
	if(strlen($_POST['email']) < 1)
	{
		$errors[] = "Email address required.";
	}

	if(strlen($_POST['password']) < 1)
	{
		$errors[] = "Password required.";
	}

	if(count($errors) > 0)
	{
		$_SESSION['errors'] = $errors;
		header("Location: index.php");
		exit();
	}
	else
	{
		$esc_email = mysqli_real_escape_string($connection, $_POST['email']);
		$esc_email = strtolower($esc_email);

		$query = "SELECT * FROM users WHERE email='{$esc_email}'";

		$user = mysqli_query($connection,$query);
		$user=$user->fetch_assoc();

		if(!empty($user))
		{
			$encrypted_password = md5($_POST['password'] . '' . $user['salt']);
			if($encrypted_password != $user['password'])
			{
				$_SESSION['errors'] = array("Bad login credentials. (pass)");
				header("Location: index.php");
				exit();
			}
			elseif($encrypted_password == $user['password'])
			{
				$_SESSION['success'] = "Login successful!";

				$_SESSION['logged_user'] = array("id" => $user['id'], "first_name" => $user['first_name']);

				header("Location: main.php");
				exit();
			}
			else
			{
				die("something else happened.");
			}
		}
		else
		{
			$_SESSION['errors'] = array("Bad login credentials.");
			header("Location: index.php");
			exit();
		}
	}

}

//log out routine
if(isset($_POST['action']) && $_POST['action'] == "logout")
{
	session_unset();
	session_destroy();
	header("Location: index.php");
	exit();
}
?>