<?php
/**
 * @file
 * Plantilla HTML5.
 */
?>
<!DOCTYPE HTML>

<html>

<head>
    <?php print redireccionar(); ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>PlaceToPay</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="js/main.js"></script> 
</head>

<body>

	<header>
	    <h1>PlaceToPay</h1>
	    <h3>Prototipo de pruebas</h3>
		<nav>
			<ul>
			    <li><a href="index.php">Inicio</a></li>
				<li><a href="index.php?log">Historia</a></li>
			</ul>
		</nav>
	</header>
	
	<section>
	  <?php print $html; ?>
	</section>

	<footer>
		<p>Augusto R. M.</p>
	</footer>

</body>

</html>

