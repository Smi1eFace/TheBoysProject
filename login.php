<?php
	if(isset($_COOKIE['UniqueIdentifier']))
		header("Location: index.php");

	include 'Tools/header.php';
?>
<body>
	<link rel="stylesheet" href="Styles/LoginStyle.css">
	<link rel="stylesheet" href="Styles/BTStyle.css">

	<div class="loginDiv">
		<h1>Login, Partner.</h1>

		<form method="POST" action="index.php">
			<div class="textBoxDiv">
				<input type="text" class="textBox" placeholder="Username" name="user" required>
			</div>
			<div class="textBoxDiv">
				<input type="password" class="textBox" placeholder="Password" name="password" required>
			</div>
			<input type="submit" class="Button" value="Login">
		</form>

		<div id="paragraphDiv">
			<?php
				if(isset($_GET['error']))
				{
					echo "<p class=\"paragraph\" id=\"p3\">ERROR: Wrong username or password.</p>";
				}
				else {
					echo "<p class=\"paragraph\" id=\"p1\">By logging in, you pledge to act with honesty and integrity, remain faithful in your actions, and guard your heart against the influence of greed.</p><p class=\"paragraph\" id=\"p2\">Remember</p><p class=\"paragraph\" id=\"p3\">\"Then he said to them, 'Watch out! Be on your guard against all kinds of greed; life does not consist in an abundance of possessions.'\"</p><p class=\"paragraph\" id=\"p4\">Luke 12:15</p>";
				}
			?>
		</div>
	</div>
</body>