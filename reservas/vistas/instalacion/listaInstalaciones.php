<script>
	// **** Petición y respuesta AJAX con JS tradicional ****

	peticionAjax = new XMLHttpRequest();

	function borrarPorAjax(idInstalacion) {
		if (confirm("¿Está seguro de que desea borrar la instalación?")) {
			idInstalacionGlobal = idInstalacion;
			peticionAjax.onreadystatechange = borradoInstalacionCompletado;
			peticionAjax.open("GET", "index.php?action=borrarInstalacionAjax&idInstalacion=" + idInstalacion, true);
			peticionAjax.send(null);
		}
	}

	function borradoInstalacionCompletado() {
		if (peticionAjax.readyState == 4) {
			if (peticionAjax.status == 200) {
				idInstalacion = peticionAjax.responseText;
				if (idInstalacion == -1) {
					document.getElementById('msjError').innerHTML = "Ha ocurrido un error al borrar la instalación";
				} else {
					document.getElementById('msjInfo').innerHTML = "Instalación borrada con éxito";
					document.getElementById('instalacion' + idInstalacion).remove();
				}
			}
		}
	}

	// **** Petición y respuesta AJAX con jQuery ****

	$(document).ready(function() {
		$(".btnBorrar").click(function() {
			if (confirm("¿Está seguro de que desea borrar la instalación?")) {
				$.get("index.php?action=borrarInstalacionAjax&idInstalacion=" + this.id, null, function(idInstalacionBorrada) {
					if (idInstalacionBorrada == -1) {
						$('#msjError').html("Ha ocurrido un error al borrar la instalación");
					} else {
						$('#msjInfo').html("Instalación borrada con éxito");
						$('#instalacion' + idInstalacionBorrada).remove();
					}
				});
			}
		});
	});
</script>



<?php
echo "<h1>Lista de instalaciones</h1>";
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
			<input type='hidden' name='action' value='buscarInstalaciones'>
           	<input type='text' name='textoBusqueda'>
			<input type='submit' value='Buscar'>
            </form><br>";

if (count($data['listaInstalaciones']) > 0) {

	// Ahora, la tabla con los datos de los instalaciones
	echo "<table border ='1'>";
	echo "<thead>";
	echo "<tr>";
	echo "<th>Nombre</th>";
	echo "<th>Descripcion</th>";
	echo "<th>Imagen</th>";
	echo "<th>Precio</th>";
	echo "<th>Modificar</th>";
	echo "<th>Borrar</th>";
	echo "</tr>";
	echo "</thead>";
	echo "<tbody>";
	foreach ($data['listaInstalaciones'] as $instalacion) {
		echo "<tr id='instalacion" . $instalacion->id . "'>";
		echo "<td>" . $instalacion->nombre . "</td>";
		echo "<td>" . $instalacion->descripcion . "</td>";
		echo "<td><img width='50' src='" . $instalacion->imagen . "'></td>";
		echo "<td>" . $instalacion->precio . "</td>";
		// Los botones "Modificar" y "Borrar" solo se muestran si hay una sesión iniciada
		if ($this->seguridad->haySesionIniciada()) {
			echo "<td><a href='index.php?action=formularioModificarInstalacion&id=" . $instalacion->id . "'>Modificar</a></td>";
			//echo "<td><a href='index.php?action=borrarInstalacion&idInstalacion=" . $instalacion->id . "'>Borrar mediante enlace</a></td>";
			echo "<td><a href='#' onclick='borrarPorAjax(" . $instalacion->id . ")'>Borrar</a></td>";
			//echo "<td><a href='#' class='btnBorrar' id='" . $instalacion->id . "'>Borrar por Ajax/jQuery</a></td>";
		}
		echo "</tr>";
	}
	echo "</tbody>";
	echo "</table>";
} else {
	// La consulta no contiene registros
	echo "No se encontraron datos";
}
