<?php
	require_once "../db_connect.php";
	require_once "../messages.php";
	require_once "header_librarian.php";
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/custom_checkbox_style.css">
		<link rel="stylesheet" type="text/css" href="css/pending_book_requests_style.css">
	</head>
	<body>
		<?php
			$query = $con->prepare("SELECT * FROM pending_book_requests;");
			$query->execute();
			$result = $query->get_result();
			$rows = mysqli_num_rows($result);
			if($rows == 0)
				echo "<h2 align='center'>Nema zahteva na čekanju!</h2>";
			else 
			{
				echo "<form class='cd-form' method='POST' action='#'>";
				echo "<legend>Zahtevi na čeknaju:</legend>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo "<table width='100%' cellpadding=10 cellspacing=10>
						<tr>
							<th></th>
							<th>Korisnik<hr></th>
							<th>Knjiga<hr></th>
							<th>Vreme<hr></th>
						</tr>";
				for($i=0; $i<$rows; $i++)
				{
					$row = mysqli_fetch_array($result);
					echo "<tr>";
					echo "<td>
							<label class='control control--checkbox'>
								<input type='checkbox' name='cb_".$i."' value='".$row[0]."' />
								<div class='control__indicator'></div>
							</label>
						</td>";
					for($j=1; $j<4; $j++)
						echo "<td>".$row[$j]."</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<br /><br /><div style='float: right;'>";
				echo "<input type='submit' value='Odbaci zahtev' name='l_reject' />&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<input type='submit' value='Dozvoli zahtev' name='l_grant'/>";
				echo "</div>";
				echo "</form>";
			}
			
			$header = 'From: <noreply@library.com>' . "\r\n";
			
			if(isset($_POST['l_grant']))
			{
				$requests = 0;
				for($i=0; $i<$rows; $i++)
				{
					if(isset($_POST['cb_'.$i]))
					{
						$request_id =  $_POST['cb_'.$i];
						$query = $con->prepare("SELECT member, book_isbn FROM pending_book_requests WHERE request_id = ?;");
						$query->bind_param("d", $request_id);
						$query->execute();
						$resultRow = mysqli_fetch_array($query->get_result());
						$member = $resultRow[0];
						$isbn = $resultRow[1];
						$query = $con->prepare("INSERT INTO book_issue_log(member, book_isbn) VALUES(?, ?);");
						$query->bind_param("ss", $member, $isbn);
						if(!$query->execute())
							die(error("Nije moguće izdati knjigu"));
						$requests++;
						
						$query = $con->prepare("SELECT email FROM member WHERE username = ?;");
						$query->bind_param("s", $member);
						$query->execute();
						$to = mysqli_fetch_array($query->get_result())[0];
						$subject = "Knjiga uspešno izdata";
						
						$query = $con->prepare("SELECT title FROM book WHERE isbn = ?;");
						$query->bind_param("s", $isbn);
						$query->execute();
						$title = mysqli_fetch_array($query->get_result())[0];
						
						$query = $con->prepare("SELECT due_date FROM book_issue_log WHERE member = ? AND book_isbn = ?;");
						$query->bind_param("ss", $member, $isbn);
						$query->execute();
						$due_date = mysqli_fetch_array($query->get_result())[0];
						$message = "Knjiga '".$title."' sa šifrom ".$isbn." je izdata. Rok za vraćanje knjige je ".$due_date.".";
						
						mail($to, $subject, $message, $header);
					}
				}
				if($requests > 0)
					echo success("Uspešno dodeljen ".$requests." zahtev");
				else
					echo error("Nije odabran nijedan zahtev");
			}
			
			if(isset($_POST['l_reject']))
			{
				$requests = 0;
				for($i=0; $i<$rows; $i++)
				{
					if(isset($_POST['cb_'.$i]))
					{
						$requests++;
						$request_id =  $_POST['cb_'.$i];
						
						$query = $con->prepare("SELECT member, book_isbn FROM pending_book_requests WHERE request_id = ?;");
						$query->bind_param("d", $request_id);
						$query->execute();
						$resultRow = mysqli_fetch_array($query->get_result());
						$member = $resultRow[0];
						$isbn = $resultRow[1];
						
						$query = $con->prepare("SELECT email FROM member WHERE username = ?;");
						$query->bind_param("s", $member);
						$query->execute();
						$to = mysqli_fetch_array($query->get_result())[0];
						$subject = "Odbijeno izdavanje knjige";
						
						$query = $con->prepare("SELECT title FROM book WHERE isbn = ?;");
						$query->bind_param("s", $isbn);
						$query->execute();
						$title = mysqli_fetch_array($query->get_result())[0];
						$message = "Zahtev za knjigom '".$title."' sa šifrom ".$isbn." je odbijen.";
						
						$query = $con->prepare("DELETE FROM pending_book_requests WHERE request_id = ?");
						$query->bind_param("d", $request_id);
						if(!$query->execute())
							die(error("Nije moguće izbrisati vrednosti"));
						
						mail($to, $subject, $message, $header);
					}
				}
				if($requests > 0)
					echo success("Uspešno izbrisan ".$requests." zahtev");
				else
					echo error("Nijedan zahtev nije označen");
			}