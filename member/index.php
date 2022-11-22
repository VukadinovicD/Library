<?php
	require_once "../db_connect.php";
	require_once "../messages.php";
	require_once "../logged_out.php";
	require_once "../header.php";
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css">
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
		
			<legend>Prijavite se kao član!</legend>
			
			<div class="error-message" id="error-message">
				<p id="error"></p>
			</div>
				<input class="m-user" type="text" name="m_user" placeholder="Korisničko ime" />
				<input class="m-pass" type="password" name="m_pass" placeholder="Lozinka" />
			</div>
			
			<input type="submit" value="Prijavi se!" name="m_login" />
			
			<br /><br /><br /><br />
			
			<p>Nemate nalog?&nbsp;<a href="register.php">Registrujte se</a>
		</form>
	</body>
	
	<?php
		if(isset($_POST['m_login']))
		{
			$query = $con->prepare("SELECT id, balance FROM member WHERE username = ? AND password = ?;");
			$query->bind_param("ss", $_POST['m_user'], $_POST['m_pass']);
			$query->execute();
			$result = $query->get_result();
			
			if(mysqli_num_rows($result) != 1)
				echo error("Nevažeće korisničko ime ili lozinka");
			else 
			{
				
					$_SESSION['type'] = "member";
					$_SESSION['id'] = $resultRow[0];
					$_SESSION['username'] = $_POST['m_user'];
					header('Location: home.php');
			}
		}
	?>
	
</html>