<?php

$usuario = $data['usuario'][0];


echo "<h1>Modificar usuario</h1>";


// Creamos el formulario con los campos del usuario
// y lo rellenamos con los datos que hemos recuperado de la BD
echo "<form action = 'index.php' method = 'post' enctype='multipart/form-data'>
            <input type='hidden' name='idUsuario' value='$usuario->id'>
            Email: <input type='text' name='email' value='$usuario->email'><br>
            Contraseña: <input type='password' name='password' value='$usuario->password'><br>
            Nombre: <input type='text' name='nombre' value='$usuario->nombre'><br>
            Primer apellido: <input type='text' name='apellido1' value='$usuario->apellido1'><br>
            Segundo apellido: <input type='text' name='apellido2' value='$usuario->apellido2'><br>
            DNI: <input type='text' name='dni' value='$usuario->dni'><br>
            Teléfono: <input type='text' name='telefono' value='$usuario->telefono'><br>
            Imagen: <input type='file' name='imagen' value='$usuario->imagen'><br>
            <img src='$usuario->imagen' width='50'><br><br>
            Tipo:"; if ($usuario->tipo ==  'admin'){
                        echo "<select name='tipo'>
                        <option value='admin' selected >admin</option>
                        <option value='user'>user</option>
                        </select><br><br>";
                    }else{
                        echo "<select name='tipo'>
                        <option value='admin'>admin</option>
                        <option value='user' selected >user</option>
                        </select><br><br>";
                    }
            

// Finalizamos el formulario
echo " <input type='hidden' name='action' value='modificarUsuario'>
            <input type='submit'>
          </form>";
echo "<p><a href='index.php?action=mostrarListaUsuarios'>Volver</a></p>";