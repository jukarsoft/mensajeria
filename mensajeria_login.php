<?php
	session_start();
	//validar si existe la sesion // indica que esta logado previamente
	if (isset($_SESSION['mensajeria'])){
		header('Location: mensajeria_menu.php');
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
	 <script type="text/javascript">
	 	//activar listener
		 window.onload = function() {
            document.getElementById('login').addEventListener('click', validarLogin);
        }    
        //validación del usuario    
		function validarLogin() {
			//alert ('validarLogin');
			var nif=document.getElementById('nif').value;
			var pass=document.getElementById('password').value;
			if (nif.trim() == '' || pass.trim() == '') {
                alert('NIF y PASSWORD son obligatorios');
                return
            }

            var datos = new FormData();
            datos.append('nif', nif);
            datos.append('password', pass);
            fetch('php/controlador_login.php', {
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
             	//alert (datos);
             	//alert (datos[0]);
             	//alert (datos[1]);
             	
                if (datos[0]!='00')  {
                	alert (`error: ${datos[0]} - ${datos[1]}`);
                } else {
                	 window.location.href = 'mensajeria_menu.php';   
                }      
               
             }) 
             .catch(function(error) {
                alert (error);
            }) 

		}

	</script>
</head>
<body>
	<div> 
		<h2>PROGRAMA DE MENSAJERIA</h2>
		<span id='mensajes'></span><br><br>
		<form id='formulario'> 
			<label>NIF Usuario: </label><input type="text" name="nif" id="nif"><br>
			<label>Password: </label><input type="password" name="password" id="password"><br><br>
			<input type="button" name="login" value="Login" id='login'>
		</form>
	</div><br>
</body>
</html>