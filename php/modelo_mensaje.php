<?php 
	//modelo login
	require 'modelo_conexion.php';
	//diseño clase Mensaje
	class Mensaje extends Conexion {

		//función acceso a bbdd mensajes y extraer los mensajes del destinatario que corresponde al usuario logado
		public function cargarMensajes ($destinatario,$pagina) {
			//variables de paginación // inicialización variables de paginación
			$filaInicial=0;
			$numFilasMostrar=5;
			try {
				$filaInicial=($pagina-1)*$numFilasMostrar;
				$sql="SELECT idmensaje, remitente, destinatario, mensaje, mensaje.fechaalta, idusuario, tipousuario, nombre, apellidos FROM mensaje INNER JOIN usuario ON remitente=idusuario WHERE destinatario=:destinatario LIMIT $filaInicial,$numFilasMostrar";
				 //prepare de la sentencia sql para acceder al usuario por nif
				$stmt = $this->conexion->prepare($sql);
				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				$stmt->bindParam(':destinatario', $destinatario);
				//Ejecutar la sentencia
				$stmt->execute();

				$array_mensajes = array();		
				while ($fila = $stmt->fetch()) {
					array_push($array_mensajes, $fila);
				}	
				//recupera el numero de filas de la seleccion
				$numFilas=$stmt->rowCount();

				$sql2 = "SELECT COUNT(*) as numeroFilas FROM mensaje WHERE destinatario=:destinatario";
				//prepare de la sentencia sql para acceder al usuario por nif
				$stmt2 = $this->conexion->prepare($sql2);
				// Especificar como se quieren devolver los datos
				$stmt2->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				$stmt2->bindParam(':destinatario', $destinatario);
				//Ejecutar la sentencia
				$stmt2->execute();
				
				$fila = $stmt2->fetch();
				//print_r($fila['numeroFilas']);	
				//recupera el numero de filas totales
				$numFilasTotales=$stmt2->rowCount();
				//echo $numFilasTotales;
				//echo "<br>";

				//calcular el número de páginas 
				$paginas=ceil($fila['numeroFilas'] / $numFilasMostrar);
				//echo $numFilas;
				//echo "<br>";
				//retorna codigo error + la lista de mensajes obtenida y el número de paginas a montar
				$codigo='00';
				$info="OK";
				//$control=array('codigo'=>$codigo, 'mensaje'=> $info);
				$respuesta= (object) [
					'codigo'=> $codigo, 
					'mensaje'=> $info, 
					'lista' => $array_mensajes, 
					'numfilas' => $numFilas,
					'paginas' => $paginas
				];
				//print_r($respuesta);
				return $respuesta;

			} catch (PDOException $e) {
				throw new Exception ($e->getMessage(), $e->getCode());	
			}
				 
		}

		//función acceso a bbdd mensajes para el borrado de los mensajes seleccionados
		public function borrarMensajes ($array) {
			try {
				$sql="DELETE FROM mensaje WHERE idmensaje IN ($array)";
				//prepare de la sentencia sql para acceder al usuario por nif
				$stmt=$this->conexion->PREPARE($sql);
				
				//inicia una transaction
				$this->conexion->beginTransaction();
				//Ejecutar la sentencia
				$stmt->execute();
				//commit a la transacción
				$this->conexion->commit();
				//numero de filas modificadas
				$numFilas=$stmt->rowCount();
				if ($numFilas==0) {
					$codigo='14';
					$mensaje='no se relizado ninguna modificación, comprobar bbdd';
				} else {
					$codigo='00';
					$mensaje='petición de BORRADO mensajes: '.$array.', realizada';
				} 
				$respuesta= (object) ['codigo'=>'00', 'mensaje'=> $mensaje];
				return $respuesta; 

			} catch (PDOException $e) {
				throw new Exception ($e->getMessage(), $e->getCode());	

			}
		}	

		//función para el acceso a bbdd de usuarios para la extracción de los usuarios - opt 0 extrae todos, opt 1 extrae todos menos el usuario logado
		public function cargarUsuarios ($user,$opt) {
			try {
				$sql="SELECT idusuario, nombre, apellidos FROM usuario";
				if ($opt=='1') {
					$sql="SELECT idusuario, nombre, apellidos FROM usuario WHERE idusuario <> :user ";
				}
				
				$stmt = $this->conexion->prepare($sql);
				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				$stmt->bindParam(':user', $user);
				//Ejecutar la sentencia
				$stmt->execute();
				$array_usuarios = array();		
				while ($fila = $stmt->fetch()) {
					array_push($array_usuarios, $fila);
					//echo "<br>";
					//print_r($libros);
				}	
				$numFilas=$stmt->rowCount();
				//echo $numFilas;
				//echo "<br>";
				//retorna codigo error + la lista de mensajes obtenida y el número de paginas a montar
				$codigo='00';
				$info="OK";
				
				$respuesta= (object) [
					'codigo'=> $codigo, 
					'mensaje'=> $info, 
					'lista' => $array_usuarios, 
					'numfilas'=> $numFilas
				];
			//print_r($respuesta);
			return $respuesta;
			} catch (PDOException $e){
				throw new Exception ($e->getMessage(), $e->getCode());	
			}
		}

		//función para acceso a bbdd y creación de los mensajes según destinos seleccionados y usuario logado
		public function crearMensajes ($remitente, $destinatarios, $mensaje) {
		//	print_r($destinatarios);
		//	print_r($remitente);
		//	print_r($mensaje);
		//$sql="INSERT INTO mensajes VALUES(NULL, :remitente, :destinatario, :mensaje, :fecha)";
			try {
				$numDestinatarios=count($destinatarios);
				//$sql="INSERT INTO mensajes VALUES (NULL, '$remitente','$undestinatario', '$mensaje', NULL)";

				$sql="INSERT INTO mensaje VALUES";
				for ($i=0; $i<$numDestinatarios; $i++) {
					$undestinatario=$destinatarios[$i];
					$sql.=" (NULL, '$remitente', '$undestinatario', '$mensaje', NULL)";
					if ($i<$numDestinatarios-1) {
						$sql.=",";
					} else {
						$sql.=";";
					}
				}
				
				$stmt=$this->conexion->PREPARE($sql);
				//bind de los parametros // asigna los valores a la sentencia preparada
				//$stmt->bindParam(':remitente', $remitente);
				//$stmt->bindParam(':undestinatario', $undestinatario);
				//$stmt->bindParam(':mensaje', $mensaje);
				//$stmt->bindParam(':fecha', $fecha->getTimestamp());
						//inicia una transaction
				$this->conexion->beginTransaction();
				//Ejecutar la sentencia
				$stmt->execute();
				//commit a la transacción
				$this->conexion->commit();
				//numero de filas modificadas
				$numFilas=$stmt->rowCount();
				$lista=implode(',',$destinatarios);
				if ($numFilas==0) {
					$codigo='14';
					$mensaje='no se han insertado los mensajes, comprobar bbdd';
				} else {
					$codigo='00';
					$mensaje='Insertados los mensajes a los usuarios: '.$lista.', realizada';
				} 
				$respuesta= (object) ['codigo'=>'00', 'mensaje'=> $mensaje];
				return $respuesta; 

			} catch (PDOException $e) {
					throw new Exception ($e->getMessage(), $e->getCode()); 
			}
		}	
		

	}  //cierra class Mensaje


//secccion para probar 
/*
	//provisional para pruebas
	$mensajes = new Mensaje();
*/

/*	//Consulta usaurios
	try {
		$tabla=$mensajes->cargarUsuarios(19);
		print_r($tabla);
	} catch (Exception $e) {
		echo $e->getCode().' '.$e->getMessage();
	}
*/
/*
	//Consulta mensajes
	try {
		$tabla=$mensajes->cargarMensajes(19,1);
		print_r($tabla);
	} catch (Exception $e) {
		echo $e->getCode().' '.$e->getMessage();
	}
*/
/*
	try {
			$tabla=$mensajes->crearMensajes(51,[19,48],'unapruebajcmv');
			print_r($tabla);
		} catch (Exception $e) {
			echo $e->getCode().' '.$e->getMessage();
		}
*/

?>