<?php
	require "../db_connect.php";
	require "../messages.php";
	require "../logged_out.php";
	require "../header.php";
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
		
		<legend>Prijavite se kao bibliotekar:</legend>
		
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
			
				<input class="l-user" type="text" name="l_user" placeholder="Korisničko ime" />
				<input class="l-pass" type="password" name="l_pass" placeholder="Lozinka" />
				<input type="submit" value="Ulogujte se!" name="l_login"/>
			
		</form>
	</body>
	
	<?php
		if(isset($_POST['l_login']))
		{
			$query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
			$query->bind_param("ss", $_POST['l_user'], $_POST['l_pass']);
			$query->execute();
			if(mysqli_num_rows($query->get_result()) != 1)
				echo error("Nevažeća forma korisničkog imena ili lozinke");
			else
			{
				$_SESSION['type'] = "librarian";
				$_SESSION['id'] = mysqli_fetch_array($result)[0];
				$_SESSION['username'] = $_POST['l_user'];
				header('Location: home.php');
			}
		}
	?>
	
</html>