<?php
	session_start();
	//logoff
	if (isset($_GET['logoff'])) {
		//borrado de la variable de sesion
		unset($_SESSION['mensajeria']);
		
	}
	
	//validar si existe la sesion // indica que esta logado previamente
	if (!isset($_SESSION['mensajeria'])){
		header('Location: mensajeria_login.php');
	} else {
		$user = $_SESSION['mensajeria']['usuario'];
		$tipo = $_SESSION['mensajeria']['tipo'];
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style type="text/css">
		div {
			width:500px; 
			border:2px solid black; 
			border-radius:5px; 
			text-align:center; 
			padding:20px; 
			margin:auto; 
			background: lightblue;
		}
		label {
			display: inline-block;
			width: 120px;
		}

		
	</style>
</head>
<body>
	<div> 
		<h2>PROGRAMA DE MENSAJERIA</h2>
		
		<a href='mensajeria_ver_mensajes_ajax.php'>Ver mensajes</a><br><br>
		
		<a href='mensajeria_enviar_mensajes.php'>Enviar mensajes</a><br><br>
		
		<a href='mensajeria_menu.php?logoff'>Logoff</a><br><br>
		
		<?php
			if ($tipo=='A') {
		?>		
		<a href='administrar_mensajes.php'>Administrar mensajes</a><br><br>
		<?php
			}
		?>		

	</div><br>
</body>
</html>