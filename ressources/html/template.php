<!-- Le template est composé des éléments de la vue du site -->

<?php 
	if(session_id() == '') session_start(); 
 	ini_set('display_errors', 1);
?>

<html>
	<head>
		<meta charset='UTF-8' >
		<link href="ressources/css/style.css" rel="stylesheet">
		<link href="ressources/css/bootstrap.css" rel="stylesheet">
      		<link href="ressources/css/main.css" rel="stylesheet">
      		<link rel="icon" type="image/png" href="ressources/pinkphone.png" />
      		<script src="ressources/js/html5shiv.js"></script>
      		<script src="ressources/js/respond.min.js"></script>
      		<script src="ressources/js/main.js"></script>
		<title>HOTLINE MONTREUIL 2 : WRONG TYPE EXCEPTION</title>
	</head>
	
	<body>
		<?php
			include_once('navbar.php');
			//include_once('footer.php');
		?>
	</body>
</html>
	
	
	
	
	
</html>




