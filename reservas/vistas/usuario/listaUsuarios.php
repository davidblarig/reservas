<script>
	// **** Petición y respuesta AJAX con JS tradicional ****

	peticionAjax = new XMLHttpRequest();

	function borrarPorAjax(idUsuario) {
		if (confirm("¿Está seguro de que desea borrar el usuario?")) {
			idUsuarioGlobal = idUsuario;
			peticionAjax.onreadystatechange = borradoUsuarioCompletado;
			peticionAjax.open("GET", "index.php?action=borrarUsuarioAjax&idUsuario=" + idUsuario, true);
			peticionAjax.send(null);
		}
	}

	function borradoUsuarioCompletado() {
		if (peticionAjax.readyState == 4) {
			if (peticionAjax.status == 200) {
				idUsuario = peticionAjax.responseText;
				if (idUsuario == -1) {
					document.getElementById('msjError').innerHTML = "Ha ocurrido un error al borrar el usuario";
				} else {
					document.getElementById('msjInfo').innerHTML = "Usuario borrado con éxito";
					document.getElementById('usuario' + idUsuario).remove();
				}
			}
		}
	}

	// **** Petición y respuesta AJAX con jQuery ****

	$(document).ready(function() {
		$(".btnBorrar").click(function() {
			if (confirm("¿Está seguro de que desea borrar el usuario?")) {
				$.get("index.php?action=borrarUsuarioAjax&idUsuario=" + this.id, null, function(idUsuarioBorrado) {
					if (idUsuarioBorrado == -1) {
						$('#msjError').html("Ha ocurrido un error al borrar el usuario");
					} else {
						$('#msjInfo').html("Usuario borrado con éxito");
						$('#usuario' + idUsuarioBorrado).remove();
					}
				});
			}
		});
	});
</script>



<?php
echo "<h1>Lista de usuarios</h1>";
// Mostramos info del usuario logueado (si hay alguno)
if ($this->seguridad->haySesionIniciada()) {
	echo "<p>Hola, " . $this->seguridad->get("nombreUsuario") . "</p>";
	echo "<p align='left'><img width='50' src='" . $this->seguridad->get("fotografiaUsuario") . "'></p>";
}
// Mostramos mensaje de error o de información (si hay alguno)
if (isset($data['msjError'])) {
	echo "<p style='color:red' id='msjError'>" . $data['msjError'] . "</p>";
} else {
	echo "<p style='color:red' id='msjError'></p>";
}
if (isset($data['msjInfo'])) {
	echo "<p style='color:blue' id='msjInfo'>" . $data['msjInfo'] . "</p>";
} else {
	echo "<p style='color:blue' id='msjInfo'></p>";
}


// Enlace a "Iniciar sesión" o "Cerrar sesión"
if (isset($_SESSION["idUsuario"])) {
	echo "<p><a href='index.php?action=cerrarSesion'>Cerrar sesión</a></p>";
} else {
	echo "<p><a href='index.php?action=mostrarFormularioLogin'>Iniciar sesión</a></p>";
}

// Primero, el formulario de búsqueda
echo "<form action='index.php'>
			<input type='hidden' name='action' value='buscarUsuarios'>
           	<input type='text' name='textoBusqueda'>
			<input type='submit' value='Buscar'>
            </form><br>";

if($data['listaUsuarios'] == null) {
	// La consulta no contiene registros
	echo "No se encontraron datos";
} else if (count($data['listaUsuarios']) > 0) {
	//echo "<p>".$data['listaUsuarios']->nombre."</p>";
	// Ahora, la tabla con los datos de los usuarios
	echo "<table border ='1'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Nombre</th>";
	echo "<th>Apellidos</th>";
	echo "<th>DNI</th>";
	echo "<th>Teléfono</th>";
	echo "<th>Modificar</th>";
	echo "<th>Borrar</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($data['listaUsuarios'] as $usuario) {
		echo "<tr id='usuario" . $usuario->id . "'>";
		echo "<td>" . $usuario->nombre . "</td>";
		echo "<td>" . $usuario->apellido1 . " " . $usuario->apellido2 . "</td>";
		echo "<td>" . $usuario->dni . "</td>";
		echo "<td>" . $usuario->telefono . "</td>";
		// Los botones "Modificar" y "Borrar" solo se muestran si hay una sesión iniciada
		if ($this->seguridad->haySesionIniciada()) {
			echo "<td><a href='index.php?action=formularioModificarUsuario&idUsuario=" . $usuario->id . "'>Modificar</a></td>";
			//echo "<td><a href='index.php?action=borrarUsuario&idUsuario=" . $usuario->id . "'>Borrar mediante enlace</a></td>";
			echo "<td><a href='#' onclick='borrarPorAjax(" . $usuario->id . ")'>Borrar</a></td>";
			//echo "<td><a href='#' class='btnBorrar' id='" . $usuario->id . "'>Borrar por Ajax/jQuery</a></td>";
		}
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
}

// El boton "Nuevo usuario" solo se muestra si hay una sesion iniciada
if ($this->seguridad->haySesionIniciada()) {
	echo "<p><a href='index.php?action=formularioInsertarUsuario'>Nuevo</a></p>";
}
