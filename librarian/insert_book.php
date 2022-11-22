<?php
	require_once "../db_connect.php";
	require_once "../messages.php";
	require_once "header_librarian.php";
?>

<html>
	<head>
		<title>Dodaj knjigu</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
	</head>
	<body>
		<form class="cd-form" method="POST" action="#">
			<legend>Unesite podatke o knjizi:</legend>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
					<input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="ISBN"/>
					<input class="b-title" type="text" name="b_title" placeholder="Naslov" />
					<input class="b-author" type="text" name="b_author" placeholder="Autor" />
				
				<div>
				<h4>Kategorija</h4>
						<select class="b-category" name="b_category">
							<option>Drama</option>
							<option>Triler</option>
							<option>Autobiografija</option>
							<option>Klasik</option>
						</select>
				</div>
					<input class="b-price" type="number" name="b_price" placeholder="Cena" />
					<input class="b-copies" type="number" name="b_copies" placeholder="Kopije" />
				
				<br />
				<input class="b-isbn" type="submit" name="b_add" value="Dodaj knjigu" />
		</form>
	<body>
	
	<?php
		if(isset($_POST['b_add']))
		{
			$query = $con->prepare("SELECT isbn FROM book WHERE isbn = ?;");
			$query->bind_param("s", $_POST['b_isbn']);
			$query->execute();
			
			if(mysqli_num_rows($query->get_result()) != 0)
				echo error_with_field("Knjiga sa tom šifrom već postoji!", "b_isbn");
			else
			{
				$query = $con->prepare("INSERT INTO book VALUES(?, ?, ?, ?, ?, ?);");
				$query->bind_param("ssssdd", $_POST['b_isbn'], $_POST['b_title'], $_POST['b_author'], $_POST['b_category'], $_POST['b_price'], $_POST['b_copies']);
				
				if(!$query->execute())
					die(error("Nije moguće dodati knjigu!"));
				echo success("Uspešno dodata knjiga");
			}
		}
	?>
</html>