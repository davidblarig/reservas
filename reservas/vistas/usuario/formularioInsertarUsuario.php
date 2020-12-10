<?php
	// Comprobamos si hay una sesion iniciada o no
		echo "<h1>Alta de usuarios</h1>";

		// Creamos el formulario con los campos del usuario
		echo "<form action = 'index.php' method = 'post' enctype='multipart/form-data'>
				Email:<input type='text' name='email'><br>
				Contraseña:<input type='password' name='password'><br>
				Nombre:<input type='text' name='nombre'><br>
				Primer Apellido:<input type='text' name='apellido1'><br>
				Segundo Apellido:<input type='text' name='apellido2'><br>
                DNI:<input type='text' name='dni'><br>
                Teléfono:<input type='text' name='telefono'><br>
				Imagen:<input type='file' name='imagen'><br>
				Tipo:<select name='tipo'>
						<option value='usuario' selected >Usuario</option>
						<option value='admin'>Admin</option>
					</select><br><br>";

		// Finalizamos el formulario
		echo "<input type='hidden' name='action' value='insertarUsuario'>
				<input type='submit'>
			</form>";
		echo "<p><a href='index.php?action=mostrarListaUsuarios'>Volver</a></p>";