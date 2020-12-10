<script>

function ejecutar_ajax() {
	peticion_http = new XMLHttpRequest();
	peticion_http.onreadystatechange = procesa_respuesta;
	emailUsuario = document.getElementById("emailUsuario").value;
	peticion_http.open('GET', 'http://localhost/reservas/index.php?action=comprobarEmailUsuario&emailUsuario=' + emailUsuario, true);
	peticion_http.send(null);
}	

function procesa_respuesta() {
	if(peticion_http.readyState == 4) {
		if(peticion_http.status == 200) {
			if (peticion_http.responseText == "0")
				document.getElementById('mensajeUsuario').innerHTML = "Error, ese usuario no existe";
			if (peticion_http.responseText == "1")
				document.getElementById('mensajeUsuario').innerHTML = "Usuario OK";
		}
	}
}	
</script>

<h1>Iniciar sesión</h1>

<?php
	if (isset($data['msjError'])) {
		echo "<p style='color:red'>".$data['msjError']."</p>";
	}
	if (isset($data['msjInfo'])) {
		echo "<p style='color:blue'>".$data['msjInfo']."</p>";
	}
?>

<form action='index.php'>
	<span id='mensajeUsuario'></span><br>
	Email: <input type='text' name='email' id='nombreUsuario' onBlur='ejecutar_ajax()'><br />
	Contraseña: <input type='password' name='pass' style="width: 135px;"><br>
	<input type='hidden' name='action' value='procesarLogin'>
	<input type='submit'>
</form>