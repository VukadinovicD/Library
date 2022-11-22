<?php
	require "../db_connect.php";
	require "../messages.php";
	require "header_librarian.php";
?>

<html>
	<head> 
		<title>Ažuriranje kopija:</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<legend>Unesite detalje</legend>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
					<input class="b-isbn" type='text' name='b_isbn' id="b_isbn" placeholder="ISBN" required />
					<input class="b-copies" type="number" name="b_copies" placeholder="Kopije" required />
					<input type="submit" name="b_add" value="Dodaj!" />
		</form>
	</body>
	
	<?php
		if(isset($_POST['b_add']))
		{
			$query = $con->prepare("SELECT isbn FROM book WHERE isbn = ?;");
			$query->bind_param("s", $_POST['b_isbn']);
			$query->execute();
			if(mysqli_num_rows($query->get_result()) != 1)
				echo error_with_field("Nevažeča šifra", "b_isbn");
			else
			{
				$query = $con->prepare("UPDATE book SET copies = copies + ? WHERE isbn = ?;");
				$query->bind_param("ds", $_POST['b_copies'], $_POST['b_isbn']);
				if(!$query->execute())
					die(error_without_field("Nije moguće dodati kopije"));
				echo success("Uspešno dodate kopije");
			}
		}
	?>
</html>