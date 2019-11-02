<?php 	
	session_start();
	//modelo login
	require 'modelo_conexion.php';

	//diseño clase Login
	class Login extends Conexion {
		public function __construct ($nif, $pass) {
			try {
				//llamar al constructor de la conexion
				parent::__construct();

				//validar datos
				$this->validar($nif,$pass);

				//comprobar login
				$this->comprobarLogin($nif,$pass);

			} catch (Exception $e){
				throw new Exception ($e->getMessage(),$e->getCode());
			}	
		}

		//validar el nif y el pass // validar los datos
		private function validar ($nif,$pass)	 {
			if (empty($nif) || empty($pass)) {
				throw new Exception ("NIF y Pass obligatorios", 10);
			}
		}

		//comprobar que el usuario es correcto
		private function comprobarLogin ($nif,$pass) {
			try {
				//prepare de la sentencia sql para acceder al usuario por nif
				$stmt = $this->conexion->prepare("SELECT * FROM usuario WHERE nif=:nif");
				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				$stmt->bindParam(':nif', $nif);
				//Ejecutar la sentencia
				$stmt->execute();

				//validar que el nif existe
				if ($stmt->rowCount()==0) {
					throw new Exception ("NIF inexistente", 11);
				}

				//validar que la pass coincida
				$usuario= $stmt->fetch();
				if ($pass != $usuario['password']) {
					throw new Exception ("PASS incorrecta", 12);
				}		
					
				//guardar datos del usuario en la sesion							
				$_SESSION['mensajeria']['usuario'] = $usuario['idusuario'];
				$_SESSION['mensajeria']['tipo'] = $usuario['tipousuario'];
				
			} catch (PDOException $e) {
				throw new Exception ($e->getMessage(), $e->getCode());	
			}
		}

		

	} //fin de clase
/*
	//provisionalmente para probar
	try {
		$login=new Login('123', 'wer');
	} catch (Exception $e) {
		echo $e->getMessage();

	}
		
*/



?>