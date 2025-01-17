<?php
	include "Tools/DB.php";
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		$username = $_POST['user'];
		$password = $_POST['password'];

		$result = Select("*", "Sec", "Username", $username);

		if ($result->num_rows === 1){
			$user = $result->fetch_assoc();
			if (password_verify($password, $user['Password'])) {
				setcookie("UniqueIdentifier",$user['UniqueIdentifier'],time()+(24*60*60*7),"/");
				header("Location: main.php");
			}
			else {
				header("Location: login.php?error=True");
			}
		}
		else {
			header("Location: login.php?error=True");
		}

	}
	elseif(isset($_COOKIE['UniqueIdentifier']))
	{
		$ui = $_COOKIE['UniqueIdentifier'];
		$result = Select("*", "Sec", "UniqueIdentifier", $_COOKIE['UniqueIdentifier']);

		if ($result->num_rows === 1) {
			header("Location: main.php");	
		} else {
			setcookie("UniqueIdentifier",'',time()-3,"/");
			header("Location: login.php");
		}
	}
	else {
		header("Location: login.php");
	}

?>
</br>
working