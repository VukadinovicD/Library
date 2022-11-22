<?php
	session_start();
	
	if(empty($_SESSION['type']))
		header("Location: ..");
	
	else if(strcmp($_SESSION['type'], "librarian") == 0)
		header("Location: ../librarian/home.php");
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header_member_style.css" />
	</head>
	<body>
		<header>
			<div id="cd-logo">
				<a href="../">
					<p>BIBLIOTEKA</p>
				</a>
			</div>
			
			<div class="dropdown">
				<button class="dropbtn">
					<p id="librarian-name"><?php echo $_SESSION['username'] ?></p>
				</button>
				<div class="dropdown-content">
					<a>
						<?php
							$query = $con->prepare("SELECT balance FROM member WHERE username = ?;");
							$query->bind_param("s", $_SESSION['username']);
							$query->execute();
							$balance = (int)$query->get_result()->fetch_array()[0];
							echo "Stanje: $".$balance;
						?>
					</a>
					<a href="books.php">Knjige</a>
					<a href="../logout.php">Odjavite se</a>
				</div>
			</div>
		</header>
	</body>
</html>