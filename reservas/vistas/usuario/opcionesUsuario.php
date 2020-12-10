<?php
    if($data['tipoUsuario'][0]->tipo == "admin"){
		echo "<h1>Vista admin</h1>";

        echo "<h2><a href='index.php?action=mostrarListaUsuarios'>Usuarios</a></h2>";
        echo "<h2><a href='index.php?action=mostrarListaInstalaciones'>Instalaciones</a></h2>";
        echo "<h2><a href='index.php?action=mostrarListaReservas'>Reservas</a></h2>";
	}else{
		echo "<h1>Vista usuario</h1>";

        echo "<h2><a href='index.php?action=mostrarListaReservas'>Reservas</a></h2>";
	}