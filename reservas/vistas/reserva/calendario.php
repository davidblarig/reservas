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
			<input type='hidden' name='action' value='buscarReservas'>
           	<input type='text' name='textoBusqueda'>
			<input type='submit' value='Buscar'>
            </form><br>";

if($data['listaReservas'] == null) {
	// La consulta no contiene registros
	echo "No se encontraron datos";
} else if (count($data['listaReservas']) > 0) {
	// Ahora, la tabla con los datos de los usuarios
	// Obtenemos todos los datos necesarios de la fecha actual
	$diaSemanaActual = date("N");				// Día de la semana (en número)
	//if ($diaSemanaActual == 0) $diaSemanaActual = 7;	// (Lo ajustamos para que Lunes sea 1 y Domingo sea 7)
	$diaMesActual = date("j");					// Día del mes (en número)
	$mesActual = date("n");						// Mes (en número). Enero = 1 y Diciembre = 12
	$nombreMesActual = date("F");				// Nombre del mes (en inglés. Si lo quieres en castellano, tendrás que hacerte un switch)
	$anoActual = date("Y");						// Año actual (en número)
	// Este echo solo tiene propósitos de depuración
    //echo "<p>Día de la semana: $diaSemanaActual, día del mes: $diaMesActual, mes: $mesActual, nombre mes: $nombreMesActual, año: $anoActual</p>";

	// Calculamos en qué día de la semana empezó el mes actual
	// diaMes / 7 nos dice la semanas que han transcurrido desde el inicio del mes. Lo que sobre
	// (es decir, diaMes % 7) son el "pico" de días que sobran de la primera semana
	$picoDias = $diaMesActual % 7; 		
	// El mes empezó en el mismo día de la semana que es hoy restándole ese pico de días
	$diaSemanaEmpiezaMes = $diaSemanaActual - $picoDias + 1;
	// Si la resta nos diera negativo o 0, lo corregimos para que dé positivo
	if ($diaSemanaEmpiezaMes < 1) $diaSemanaEmpiezaMes = $diaSemanaEmpiezaMes + 7;
	// Este echo solo tiene propósitos de depuración
	//echo "<p>Pico de días: $picoDias. El mes empezó en $diaSemanaEmpiezaMes</p>";

	$diasMes = date("t");	//Número de días del mes dado

	//echo "<p>$nombreMesActual tiene $diasMes dias</p>";
	// Ya tenemos todos los datos. Vamos a generar la tabla con el mes actual
	echo "<table border='1'>";
	echo "<tr><td colspan='7'>$nombreMesActual $anoActual</td></tr>";
	echo "<tr><td>L</td><td>M</td><td>X</td><td>J</td><td>V</td><td>S</td><td>D</td></tr><tr>";
	$cont = 0;
	for($semana=1; $semana<=6; $semana++){
		for($diaS=1; $diaS<=7; $diaS++){
			if($semana==1 && $diaSemanaEmpiezaMes==$diaS) $cont = 1;
			if($cont>0 && $cont<=$diasMes) {
				echo "<td>$cont</td>";
				$cont++;
			}else{
				echo "<td>&nbsp</td>";
			}
		}
		echo "</tr>";
	}


	/*
		// Los botones "Modificar" y "Borrar" solo se muestran si hay una sesión iniciada
		if ($this->seguridad->haySesionIniciada()) {
			echo "<td><a href='index.php?action=formularioModificarReserva&idReserva=" . $reserva->id . "'>Modificar</a></td>";
			//echo "<td><a href='index.php?action=borrarReserva&idReserva=" . $reserva->id . "'>Borrar mediante enlace</a></td>";
			//echo "<td><a href='#' onclick='borrarPorAjax(" . $reserva->id . ")'>Borrar por Ajax/JS</a></td>";
			echo "<td><a href='#' class='btnBorrar' id='" . $reserva->id . "'>Borrar por Ajax/jQuery</a></td>";
		}
		echo "</tr>";
	}*/
	echo "</table>";
}
	