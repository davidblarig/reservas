<?php
include_once("vista.php");
include_once("modelos/seguridad.php");
include_once("modelos/usuario.php");
include_once("modelos/instalacion.php");
include_once("modelos/reserva.php");

class Controlador
{

	private $vista, $usuario, $reserva, $instalacion, $horario;

	/**
	 * Constructor. Crea las variables de los modelos y la vista
	 */
	public function __construct() {
		$this->vista = new Vista();
		$this->seguridad = new Seguridad();
		$this->usuario = new Usuario();
		$this->reserva = new Reserva();
		$this->instalacion = new Instalacion();
	}

	/**
	 * Muestra el formulario de login
	 */
	public function mostrarFormularioLogin() {
		$this->vista->mostrar("usuario/formularioLogin");
	}

	/**
	 * Procesa el formulario de login e inicia la sesión
	 */
	public function procesarLogin() {
		$usr = $_REQUEST["email"];
		$pass = $_REQUEST["pass"];

		$usuario = $this->usuario->buscarUsuario($usr, $pass);
		
		if ($usuario) {
			$this->seguridad->abrirSesion($usuario);
			$this->comprobarTipoUsuario();
		} else {
			// Error al iniciar la sesión
			$data['msjError'] = "Nombre de usuario o contraseña incorrectos";
			$this->vista->mostrar("usuario/formularioLogin", $data);
		}
	}

	/**
	 * Cierra la sesión
	 */
	public function cerrarSesion() {
		$this->seguridad->cerrarSesion();
		$data['msjInfo'] = "Sesión cerrada correctamente";
		$this->vista->mostrar("usuario/formularioLogin", $data);
	}

	/**
	 * Comprueba el email del usuario
	 */
	public function comprobarEmailUsuario() {
		$emailUsuario = $_REQUEST["emailUsuario"];
		$result = $this->usuario->existeEmail($nombreEmail);
		echo $result;
	}

	/**
	 * Comprueba el tipo de usuario
	 */
	public function comprobarTipoUsuario() {
		$usr = $_REQUEST["email"];
		$pass = $_REQUEST["pass"];

		$data['tipoUsuario'] = $this->usuario->getTipo($usr, $pass);
		$this->vista->mostrar("usuario/opcionesUsuario", $data);
	}
	
	/**
	 * Muestra una lista con todos los usuarios
	 */
	public function mostrarListaUsuarios() {
		$data['listaUsuarios'] = $this->usuario->getAll();
		$this->vista->mostrar("usuario/listaUsuarios", $data);
	}

	/**
	 * Lanza una búsqueda de usuarios y muestra el resultado
	 */
	public function buscarUsuarios() {
		// Recuperamos el texto de búsqueda de la variable de formulario
		$textoBusqueda = $_REQUEST["textoBusqueda"];
		// Lanzamos la búsqueda y enviamos los resultados a la vista de lista de usuarios
		$data['listaUsuarios'] = $this->usuario->busquedaAproximada($textoBusqueda);
		$data['msjInfo'] = "Resultados de la búsqueda: \"$textoBusqueda\"";
		$this->vista->mostrar("usuario/listaUsuarios", $data);

	}

	/**
	 * Modifica un usuario en la base de datos
	 */
	public function formularioModificarUsuario() {
		if ($this->seguridad->haySesionIniciada()) {
			$id = $_REQUEST["idUsuario"];
			$data['usuario'] = $this->usuario->get($id);
			$this->vista->mostrar('usuario/formularioModificarUsuario', $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	public function modificarUsuario() {
		if ($this->seguridad->haySesionIniciada()) {

			//Lanzamos la consulta a la bd
			$result = $this->usuario->update();
			
			if ($result == 1) {
				$data['msjInfo'] = "Usuario actualizado con éxito";
			}else {
				$data['msjError'] = "Error al actualizar el usuario";
			}
			$data['listaUsuarios'] = $this->usuario->getAll();
			$this->vista->mostrar("usuario/listaUsuarios", $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Inserta un usuario en la base de datos
	 */
	public function insertarUsuario() {
			
		if ($this->seguridad->haySesionIniciada()) {
			// Vamos a procesar el formulario de alta de usuarios
			// Primero, recuperamos todos los datos del formulario
			// Ahora insertamos el usuario en la BD
			$result = $this->usuario->insert();

			// Lanzamos el INSERT contra la BD.
			if ($result == 1) {
				// Tenemos que averiguar que id se ha asignado al usuario que acabamos de insertar
				$ultimoId = $this->usuario->getLastId();
				$data['msjInfo'] = "Usuario insertado con exito";
			} else {
				// Si la insercion del usuario ha fallado, mostramos mensaje de error
				$data['msjError'] = "Ha ocurrido un error al insertar el usuario. Por favor, intentelo mas tarde.";
			}
			$data['listaUsuarios'] = $this->usuario->getAll();
			$this->vista->mostrar("usuario/listaUsuarios", $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
			
	}

	public function formularioInsertarUsuario() {
		if ($this->seguridad->haySesionIniciada()) {
			$this->vista->mostrar('usuario/formularioInsertarUsuario');
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Elimina un usuario de la base de datos
	 */
	public function borrarUsuario()
	{
		if (isset($_SESSION["idUsuario"])) {
			// Recuperamos el id del usuario
			$id = $_REQUEST["idUsuario"];
			// Eliminamos el usuario de la BD
			$result = $this->usuario->delete($id);
			if ($result == 0) {
				$data['msjError'] = "Ha ocurrido un error al borrar el usuario. Por favor, inténtelo de nuevo";
			} else {
				$data['msjInfo'] = "Usuario borrado con éxito";
			}
			// Mostramos la lista de usuarios actualizada
			$data['listaUsuarios'] = $this->usuario->getAll();
			$this->vista->mostrar("usuario/listaUsuarios", $data);
		} else {
			$data['msjError'] = "No tienes permisos para hacer eso";
			$this->vista->mostrar("usuario/formularioLogin", $data);
		}
	}

	/**
	 * Elimina un usuario de la base de datos (petición por ajax)
	 */
	public function borrarUsuarioAjax()
	{
		if ($this->seguridad->haySesionIniciada()) {
			// Recuperamos el id del usuario
			$idUsuario = $_REQUEST["idUsuario"];
			// Eliminamos el usuario de la BD
			$result = $this->usuario->delete($idUsuario);
			if ($result == 0) {
				// Error al borrar. Enviamos el código -1 al JS
				echo "-1";
			}
			else {
				// Borrado con éxito. Enviamos el id del libro a JS
				echo $idUsuario;
			}
		} else {
			echo "-1";
		}
	}

	/**
	 * Muestra una lista con todas las instalaciones
	 */
	public function mostrarListaInstalaciones() {
		$data['listaInstalaciones'] = $this->instalacion->getAll();
		$this->vista->mostrar("instalacion/listaInstalaciones", $data);
	}

	/**
	 * Lanza una búsqueda de instalaciones y muestra el resultado
	 */
	public function buscarInstalaciones()
	{
		// Recuperamos el texto de búsqueda de la variable de formulario
		$textoBusqueda = $_REQUEST["textoBusqueda"];
		// Lanzamos la búsqueda y enviamos los resultados a la vista de lista de instalaciones
		$data['listaInstalaciones'] = $this->instalacion->busquedaAproximada($textoBusqueda);
		$data['msjInfo'] = "Resultados de la búsqueda: \"$textoBusqueda\"";
		$this->vista->mostrar("instalacion/listaInstalaciones", $data);

	}

	/**
	 * Modifica una instalacion en la base de datos
	 */
	public function formularioModificarInstalacion() {
		if ($this->seguridad->haySesionIniciada()) {
			$idUsuario = $_SESSION["id"];
			$id = $_REQUEST["id"];
			$data['instalacion'] = $this->instalacion->get($id);
			$this->vista->mostrar('instalacion/formularioModificarInstalacion', $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	public function modificarInstalacion() {
		if ($this->seguridad->haySesionIniciada()) {

			//Lanzamos la consulta a la bd
			$result = $this->instalacion->update();
			
			if ($result == 1) {
				$data['msjInfo'] = "Instalación actualizada con éxito";
			}else {
				$data['msjError'] = "Error al actualizar la instalación";
			}
			$data['listaInstalaciones'] = $this->instalacion->getAll();
			$this->vista->mostrar("instalacion/listaInstalaciones", $data);
		} else {
			$this->seguridad->errorAccesoNoPermitido();
		}
	}

	/**
	 * Elimina una instalación de la base de datos
	 */
	public function borrarInstalacion()
	{
		if (isset($_SESSION["idUsuario"])) {
			// Recuperamos el id de la instalación
			$id = $_REQUEST["idInstalacion"];
			// Eliminamos la instalción de la BD
			$result = $this->instalacion->delete($id);
			if ($result == 0) {
				$data['msjError'] = "Ha ocurrido un error al borrar la instalación. Por favor, inténtelo de nuevo";
			} else {
				$data['msjInfo'] = "Instalación borrada con éxito";
			}
			// Mostramos la lista de instalaciones actualizada
			$data['listaInstalaciones'] = $this->instalacion->getAll();
			$this->vista->mostrar("instalacion/listaInstalaciones", $data);
		} else {
			$data['msjError'] = "No tienes permisos para hacer eso";
			$this->vista->mostrar("usuario/formularioLogin", $data);
		}
	}

	/**
	 * Elimina una instalación de la base de datos (petición por ajax)
	 */
	public function borrarInstalacionAjax()
	{
		if ($this->seguridad->haySesionIniciada()) {
			// Recuperamos el id de la instalación
			$idInstalacion = $_REQUEST["idInstalacion"];
			// Eliminamos la instalación de la BD
			$result = $this->instalacion->delete($idInstalacion);
			if ($result == 0) {
				// Error al borrar. Enviamos el código -1 al JS
				echo "-1";
			}
			else {
				// Borrado con éxito. Enviamos el id del libro a JS
				echo $idInstalacion;
			}
		} else {
			echo "-1";
		}
	}

	/**
	 * Muestra una lista con todas las reservas
	 */
	public function mostrarListaReservas() {
		$data['listaReservas'] = $this->reserva->getAll();
		$this->vista->mostrar("reserva/calendario", $data);
	}

}