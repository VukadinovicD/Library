<?php
	session_start();
	
	if(empty($_SESSION['type']))
		header("Location: ..");
	
	else if(strcmp($_SESSION['type'], "member") == 0)
		header("Location: ../member/home.php");
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/header_librarian_style.css" />
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
					<a href="../logout.php">Odjavite se</a>
				</div>
			</div>
		</header>
	</body>
</html>