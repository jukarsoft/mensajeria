<?php 
	//controlador login
	//modelo
	require 'modelo_login.php';

	$nif = $_POST['nif'];
	$pass = $_POST['password'];
	//$nif = '5656455';
	//$pass = 'torrente';


	try {
		//instancia de la clase Login
		$login = new Login($nif, $pass);
		$codigo='00';
		$mensaje='logado';
		$array=array($codigo,$mensaje);
		$respuesta=json_encode($array);
		echo $respuesta;
		
		
	} catch (Exception $e) {
		$respuesta=array($e->getCode(),$e->getMessage());
		$error=json_encode($respuesta);
		echo $error;
		
	}





?>