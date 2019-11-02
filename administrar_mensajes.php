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
	<script type="text/javascript" src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
	<style type="text/css">
		div {
			width:500px; 
			border:2px solid black; 
			border-radius:5px; 
			text-align:justify; 
			padding:20px; 
			margin:auto; 
			background: lightblue;
		}
		a {margin: 0px 5px;}
		#paginas {cursor:pointer;}
	</style>
	<script type="text/javascript">
		var user=null;	
        var pagina=1;
		//activar listener
		 window.onload = function() {
            document.getElementById('borrar').addEventListener('click', borrarMensajes);
             document.getElementById('listausuarios').addEventListener('change', usuarioMensajes);
            obtenerUsuarios();	
        }   

        //obtener los usuarios para montar el combo 	
        function obtenerUsuarios() {
        	//alert ('obtenerUsuarios');
        	var datos = new FormData();
			datos.append('opcion','M');
			datos.append('selec','0');
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

        //Montar la salida de los usuarios en un combo  en la página	
        function mostrarUsuarios(usuarios) {
        	//alert ('mostrarUsuarios');
        	document.getElementById('listausuarios').innerHTML="";
        	option="";
        	option+=`<option disabled selected>Seleccionar un usuario</option><br>`;
        	for (i in usuarios) {
        			option+=`<option value='${usuarios[i].idusuario} '>${usuarios[i].nombre} ${usuarios[i].apellidos}</option><br>`;
        	}		
	       	document.getElementById('listausuarios').innerHTML=option;		

        } 

        //Al seleccionar un usuario, se buscar los mensajes 	
        function usuarioMensajes() {
			//alert ('usuarioMensaje');
			pagina='1';
			user=document.getElementById('listausuarios').value;
			//alert(user);
			//Obtener mensajes recibidos por el usuario seleccionado
			cargarMensajes(user, pagina);	
        }	

        //cargar los mensajes en página 	
        function cargarMensajes(user, pagina) {
        	//alert ('cargarMensajes');
        	var datos = new FormData();
			datos.append('opcion','X');
			datos.append('user',user);
			datos.append('pagina',pagina);
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
                mostrarMensajes(datos.lista);
                mostrarPaginas(datos.paginas);    
               
             }) 
             .catch(function(error) {
                alert (error);
            }) 
        }


		//Montar la salida de los mensajes       
        function mostrarMensajes(mensajes) {
        	//alert ('mostrarMensajes');
        	//alert (mensajes);
        	console.log(mensajes);
        	document.getElementById('mensajes').innerHTML="";
        	linea="";
        	for (i in mensajes) {
        		autor=mensajes[i]['nombre']+' '+mensajes[i]['apellidos'];
        		fechaalta=mensajes[i]['fechaalta'];
        		idmensaje=mensajes[i]['idmensaje'];
        		mensaje=mensajes[i]['mensaje'];

				linea+=`<input type='checkbox' name='checkbox' class='check' value='${idmensaje}'>`;
				linea+="<label class='linea'>";
					linea+=`Autor: ${autor} ${fechaalta}`;
					linea+=`</label>`;
					//linea+=`<br>`;
				linea+="<p class='linea'>";
					linea+=`Mensaje num: ${idmensaje} -> ${mensaje}`;
					linea+=`</p>`;
					linea+=`<hr>`;
			}

			document.getElementById('mensajes').innerHTML=linea;

        }	
		          
        //montar los listener 
        function mostrarPaginas(paginas) {
            var enlaces = '';
            for (i=1; i <= paginas; i++) {
                if (i==pagina) {
                    enlaces+= "<span style='font-weight:bold; font-size:large;'>" + i + "</span>&nbsp&nbsp&nbsp ";
                } else {
                    enlaces+= "<span> " + i + "</span>&nbsp&nbsp&nbsp ";
                }
                
            }
            document.getElementById('paginas').innerHTML = enlaces;
            //activar los listener para la paginación (id + span)
            var span=document.querySelectorAll('#paginas span');

            for (i=0; i<span.length; i++) {
                span[i].addEventListener('click', function() {
                    //recuperar el número de página 
                    pagina=this.innerText;
                    //llamar a la función consultarLibros
                    cargarMensajes(user,pagina);
                })
            }
        } 

        //borrar mensajes    
        function borrarMensajes() {
        	//alert ('borrarMensajes');
        	//recuperar checks seleccionados
        	var tmpContador=0
			var checks=document.querySelectorAll('.check');
			
			//guardamos los equipos seleccionados en un array
			var listaMensajes=[];
			
			for (i=0;i<checks.length;i++) {
				tmpContador++;
				if (checks[i].checked) {
					listaMensajes.push(checks[i].value);
				}
			}
			if (listaMensajes.length==0) {
				alert('no se ha seleccionado ningún mensaje para borrar');
				return
			}

			var text=listaMensajes.toString();
			var datos = new FormData();
			datos.append('opcion','B');
			datos.append('array',text);
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
				if (tmpContador==listaMensajes.length) {
					pagina=1;
				}

				cargarMensajes(user, pagina);

				//datos es un object
				if (codigo!='00') {
					throw mensaje, codigo;
				} else {
					alert (`borrado realizado - ${codigo} - ${mensaje}`);
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
		<center>
		<h2>PROGRAMA DE MENSAJERIA</h2>
		<h3>Mensajes recibidos</h3> 
		</center>
			
			<select name="listausuarios" id="listausuarios">
				<option value="Seleccionar un usuario" disabled></option>
				
			</select>	
			<br><br>			

			<span id='mensajes'></span>
			<input type='button' value='Borrar mensajes' id='borrar'>
		<!--/form--><br><br>
		<center id='paginas'></center><br>
		<a href='mensajeria_menu.php'>Volver a menu</a>
	</div><br>
</body>
</html>