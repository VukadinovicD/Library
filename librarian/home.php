<?php
	require_once "../db_connect.php";
	require_once "header_librarian.php"; 
?>

<html>
	<head>
		<title>Dobro došli!</title>
		<link rel="stylesheet" type="text/css" href="css/home_style.css" />
	</head>
	<body>
		<div id="allTheThings">
			<a href="registrations.php">
				<input type="button" value="Zahtevi za registraciju" />
			</a><br />
			<a href="book_requests.php">
				<input type="button" value="Zahtevi za knjigama" />
			</a><br />
			<a href="insert_book.php">
				<input type="button" value="Dodavanje knjiga" />
			</a><br />
			<a href="copies.php">
				<input type="button" value="Ažuriranje kopija" />
			</a><br />
			<a href="balance.php">
				<input type="button" value="Ažuriranje stanja" />
			</a><br /><br />
		</div>
	</body>
</html>