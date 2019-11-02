<?php 
	session_start();
	//controlador login
	//modelo
	require 'modelo_mensaje.php';

	try {

		//validar si existe la sesion // indica que esta logado previamente
		if (!isset($_SESSION['mensajeria'])){
			header('Location: mensajeria_login.php');
		} else {
			$user = $_SESSION['mensajeria']['usuario']; 
			$tipo = $_SESSION['mensajeria']['tipo'];
		}
			
		//instancia de la clase
		$mensajes = new Mensaje();
		$opcion=$_POST['opcion'];

		switch ($opcion) {
			//carga mensajes del usuario seleccionado  (opcion administrador)
			case 'X':
				$userSel=$_POST['user'];
				$pagina = $_POST['pagina'];
				$array=$mensajes->cargarMensajes($userSel, $pagina);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($array);
				echo $respuesta;
				break;
			//carga mensajes del usuario logado	
			case 'C':
				$pagina = $_POST['pagina'];
				$array=$mensajes->cargarMensajes($user,$pagina);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($array);
				echo $respuesta;
				break;
			//borrado de los mensajes que se han marcado en el checkbox	
			case 'B':
				$array=$_POST['array'];
				$datos=$mensajes->borrarMensajes($array);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($datos);
				echo $respuesta;
				break;
			//carga los usuarios 
			//opcion 0 - todos (utilizado cuando es administrador)
			//opcion 1 - todos (excepto el usuario logado)	
			case 'M':
				$selec=$_POST['selec'];	
				$usuarios=$mensajes->cargarUsuarios($user,$selec);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($usuarios);
				echo $respuesta;
				break;
			//creación de nuevos mensajes a 1 o más destinatarios	
			case 'I':
				$destinatarios=$_POST['array'];
				$arrayDestinatarios = explode(',', $destinatarios); 
				$mensaje=$_POST['mensaje'];
				$datos=$mensajes->crearMensajes($user,$arrayDestinatarios,$mensaje);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($datos);
				echo $respuesta;
			break;
			default:
				break;
		}
		

	} catch (Exception $e){
		$respuesta=array($e->getCode(),$e->getMessage());
		$error=json_encode($respuesta);
		echo $error;

	}

?>