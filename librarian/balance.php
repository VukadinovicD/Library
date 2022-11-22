<?php
	require_once "../db_connect.php";
	require_once "../messages.php";
	require_once "header_librarian.php";
?>

<html>
	<head>
		<title>Ažuriranje stanja:</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<legend>Unesite detalje:</legend>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
					<input class="m-user" type='text' name='m_user' id="m_user" placeholder="Korisničko ime" required />
					<input class="m-balance" type="number" name="m_balance" placeholder="Izmena stanja" required />
					<input type="submit" name="m_add" value="Ažuriraj" />
		</form>
	</body>
	
	<?php
		if(isset($_POST['m_add']))
		{
			$query = $con->prepare("SELECT username FROM member WHERE username = ?;");
			$query->bind_param("s", $_POST['m_user']);
			$query->execute();
			if(mysqli_num_rows($query->get_result()) != 1)
				echo error("Korisnik sa datim korisničkim imenom ne postoji!", "m_user");
			else
			{
				$query = $con->prepare("UPDATE member SET balance = balance + ? WHERE username = ?;");
				$query->bind_param("ds", $_POST['m_balance'], $_POST['m_user']);
				if(!$query->execute())
					die(error("Nije moguće ažurirati stanje"));
				echo success("Uspešno ažurirano stanje");
			}
		}
	?>
</html>