<?php
	session_start();
	if (isset($_SESSION['mensajeria'])){
		$user = $_SESSION['mensajeria']['usuario'];
		$tipo = $_SESSION['mensajeria']['tipo'];
	} else {
		header('Location: mensajeria_login.php');
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
	</style>
	<script type="text/javascript">
		//activar listener
		 window.onload = function() {
		 	//boton enviar mensajes  - activar listener
            document.getElementById('enviar').addEventListener('click', enviarMensaje);
            //cargar los todos los usuarios - lista tabla	
            cargarUsuarios();

        }

        //obtiene los usuarios de la tabla usuarios	
        function cargarUsuarios() {
        	//alert ('cargarUsuarios');

        	//alert ('cargarMensajes');
        	var datos = new FormData();
			datos.append('opcion','M');
			datos.append('selec','1');
			fetch('php/controlador_mensajes.php', {
                method: 'POST',
                body: datos
            })
            .then (function(respuesta) {
               //primera respuesta del servidor como que ha recibido la petición
                if (respuesta.ok) {
                    return respuesta.json();
                } else {
                    console.log(respuesta);
                    throw "error en la llamada AJAX", 88;
                }
             })
             .then (function (datos) {
             	console.log(datos);

                if (datos.codigo!='00')  {
                	throw datos.mensaje, datos.codigo;
                	return
                } 
                mostrarUsuarios(datos.lista);
               
             }) 
             .catch(function(error) {
                alert (error);
            }) 

        }
        //Montar la lista de usuarios en tabla y mostrar 
        function mostrarUsuarios(usuarios) {
        	//alert ('mostrarUsuarios');
        	document.getElementById('listausuarios').innerHTML="";
        	var tabla="<tr><th>Nombre</th><th>Apellidos</th><th>Mandar mensaje</th></tr>";
			for (i in usuarios) {
				tabla+="<tr class='tr'>";
					tabla+=`<td>${usuarios[i].nombre}</td>`;
					tabla+=`<td>${usuarios[i].apellidos}</td>`;
					tabla+=`<td><input type='checkbox' name='checkbox' class='check' value='${usuarios[i].idusuario}'></td>`;					
				tabla+="</tr>";
			}
			document.getElementById('listausuarios').innerHTML+=tabla;
        }

        //Enviar mensaje a los destinatarios seleccionados	
        function enviarMensaje() {
        	//alert ('enviarMensaje');
        	//recuperar checks seleccionados
			//var checks=document.querySelectorAll('.check');
			var checks=document.querySelectorAll('input[type=checkbox]:checked');
			
			//guardamos los equipos seleccionados en un array
			var listaUsuarios=[];
			
			for (i=0;i<checks.length;i++) {
				listaUsuarios.push(checks[i].value);
			}
/*			for (i=0;i<checks.length;i++) {
				if (checks[i].checked) {
					listaUsuarios.push(checks[i].value);
				}
			} */
			if (listaUsuarios.length==0) {
				alert('no se ha seleccionado ningún usuario');
				return
			}

			//var text=listaUsuarios.toString();
			mensaje=document.getElementById('mensaje').value;
			var datos = new FormData();
			datos.append('opcion', 'I');
			datos.append('array', listaUsuarios);
			datos.append('mensaje', mensaje);
			fetch ('php/controlador_mensajes.php', {
				method: 'POST',
				body: datos
			})
			.then(function(respuesta) {
				if (respuesta.ok) {
					//cambiar el json a text, si queremos ver el error
					return respuesta.json();
				} else {
					throw "error en la petición AJAX",88;
				}
			})
			.then(function(datos) {
				console.log(datos);
				//alert(datos);

				codigo=datos.codigo;
				mensaje=datos.mensaje;
				
				//datos es un array js
				if (codigo!='00') {
					throw mensaje, codigo;
				} else {
					alert (`creado nuevos mensajes - ${codigo} - ${mensaje}`);
				}
				document.getElementById('mensaje').value="";
				for (i=0;i<checks.length;i++) {
						checks[i].checked=false;
				}	
				
			})
			.catch(function (error) {
				alert (error);
				if (error!='00') {
					alert (error);
				}	
			})

        }
	</script>
</head>
<body>
	<div> 
		<h2>PROGRAMA DE MENSAJERIA</h2>
		<center>
		<h3>Seleccionar destinatarios</h3>
		<span id='mensajes'></span><br><br>
		<form> 
			<table border='2' id='listausuarios'>
				<tr><th>Nombre</th><th>Apellidos</th><th>Mandar mensaje</th></tr>
				
			</table>
			<br>
			<textarea style="width:300px; height:100px" id="mensaje"></textarea><br><br>
			<input type="button" id="enviar" value="Enviar mensaje" >
		</form>
		</center><br><br>
		<a href='mensajeria_menu.php'>Volver a menu</a>
	</div><br>
</body>
</html>