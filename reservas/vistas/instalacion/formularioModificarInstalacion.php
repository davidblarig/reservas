<?php

    $instalacion = $data['instalacion'][0];

    echo "<h1>Modificar Instalación</h1>";
    // Creamos el formulario con los campos de la instalacion
    // y lo rellenamos con los datos que hemos recuperado de la BD
    echo "<form action = 'index.php' method = 'post' enctype='multipart/form-data'>
        <input type='hidden' name='id' value='$instalacion->id'>
        Nombre:<input type='text' name='nombre' value='$instalacion->nombre'><br>
        Descripcion:<input type='text' name='descripcion' value='$instalacion->descripcion'><br>
        Imagen:<input type='file' name='imagen' value='$instalacion->imagen'><br>
        Precio:<input type='text' name='precio' value='$instalacion->precio'><br>
        <img src=".$instalacion->imagen." width='50'><br><br>";
    echo "<input type='hidden' name='action' value='modificarInstalacion'>
          <input type='submit'>
        </form>";
    echo "<p><a href='index.php?action=mostrarListaInstalaciones'>Volver</a></p>";