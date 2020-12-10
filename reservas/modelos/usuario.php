<?php
    include_once("DB.php");

    class Usuario {
        private $db;
        
        /**
         * Constructor. Establece la conexión con la BD y la guarda
         * en una variable de la clase
         */
        public function __construct() {
            $this->db = new DB();
        }

       
        /**
         * Busca un usuario por email de usuario y password
         * @param email El email del usuario
         * @param password La contraseña del usuario
         * @return True si existe un usuario con ese nombre y contraseña, false en caso contrario
         */
        public function buscarUsuario($email, $password) {

            $usuario = $this->db->consulta("SELECT id, email, nombre, tipo, imagen FROM usuario WHERE email = '$email' AND password = '$password'");
            
            if ($usuario) {
                return $usuario;
            } else {
                return null;
            }

        }

        public function get($id) {
            if ($result = $this->db->consulta("SELECT * FROM usuario WHERE id = '$id'")) {
                return $result;
            } else {
                return null;
            }
        }

        public function getAll() {
            if ($result = $this->db->consulta("SELECT * FROM usuario")) {
                return $result;
            } else {
                return null;
            }
        }

        public function existeEmail($emailUsuario) {
            $result = $this->db->consulta("SELECT * FROM usuario WHERE email = '$emailUsuario'");
            if ($result != null)
                return 1;
            else  
                return 0;

        }

        public function delete($id){
            $result = $this->db->manipulacion("DELETE FROM usuario WHERE id = '$id'");
            return $result;
        }

        public function getTipo($email, $password){
            $tipo = $this->db->consulta("SELECT tipo FROM usuario WHERE email = '$email' AND password = '$password'");
            if ($tipo) {
                return $tipo;
            } else {
                return null;
            }
        }

        public function getLastId() {
            $result = $this->db->consulta("SELECT MAX(id) AS ultimaId FROM usuario");
            $id = $result->ultimaId;
            return $id;
        }

        /** 
        * Realiza una búsqueda del usuario
        * @param textoBusqueda El texto de búsqueda
        * @return Un array de objetos con los datos de los usuarios encontrados
        */
        public function busquedaAproximada($textoBusqueda) {
            // Buscamos los usuarios que coincidan con el texto de búsqueda
            $result = $this->db->consulta("SELECT * FROM usuario
				        WHERE nombre LIKE '%$textoBusqueda%'
				        OR apellido1 LIKE '%$textoBusqueda%'
				        OR apellido2 LIKE '%$textoBusqueda%'
				        OR dni LIKE '%$textoBusqueda%'
                        OR telefono LIKE '%$textoBusqueda%'
				        ORDER BY nombre");
		    if ($result) {
                return $result;
            } else {
                return null;
            }

        }

        public function update() {
            // Primero, recuperamos todos los datos del formulario
            $id = $_REQUEST["idUsuario"];
            $email = $_REQUEST["email"];
            $password = $_REQUEST["password"];
            $nombre = $_REQUEST["nombre"];
            $apellido1 = $_REQUEST["apellido1"];
            $apellido2 = $_REQUEST["apellido2"];
            $dni = $_REQUEST["dni"];
            $telefono = $_REQUEST["telefono"];
            $tipo = $_REQUEST["tipo"];
            $imagen = $_REQUEST["imagen"];

            $result = $this->db->manipulacion("UPDATE usuario SET
                                email = '$email',
								nombre = '$nombre',
								apellido1 = '$apellido1',
								apellido2 = '$apellido2',
								dni = '$dni',
								telefono = '$telefono',
                                tipo = '$tipo',
                                imagen = '$imagen'
                                WHERE id = '$id'");
            return $result;
        }

        public function insert() {
            $email = $_REQUEST["email"];
            $password = $_REQUEST["password"];
            $nombre = $_REQUEST["nombre"];
            $apellido1 = $_REQUEST["apellido1"];
            $apellido2 = $_REQUEST["apellido2"];
            $dni = $_REQUEST["dni"];
            $telefono = $_REQUEST["telefono"];
            $tipo = $_REQUEST["tipo"];
            $imagen = $_REQUEST["imagen"];

            $result = $this->db->manipulacion("INSERT INTO usuario (email, password, nombre, apellido1, apellido2, dni, telefono, tipo, imagen) 
                                    VALUES ('$email', '$password', '$nombre', '$apellido1', '$apellido2', '$dni', '$telefono', '$tipo', '$imagen')");
            return $result;
        }

    }