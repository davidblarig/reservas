<?php
    include_once("DB.php");

    class Instalacion {
        private $db;
        
        /**
         * Constructor. Establece la conexión con la BD y la guarda
         * en una variable de la clase
         */
        public function __construct() {
            $this->db = new DB();
        }

        public function get($id) {
            if ($result = $this->db->consulta("SELECT * FROM instalacion WHERE id = '$id'")) {
                return $result;
            } else {
                return null;
            }
        }

        public function getAll() {
            if ($result = $this->db->consulta("SELECT * FROM instalacion")) {
                return $result;
            } else {
                return null;
            }
        }

        public function delete($id){
            $result = $this->db->manipulacion("DELETE FROM instalacion WHERE id = '$id'");
            return $result;
        }

        /** 
        * Realiza una búsqueda de la instalación
        * @param textoBusqueda El texto de búsqueda
        * @return Un array de objetos con los datos de las instalaciones encontradas
        */
        public function busquedaAproximada($textoBusqueda) {
            // Buscamos las instalaciones que coincidan con el texto de búsqueda
            $result = $this->db->consulta("SELECT * FROM instalacion
				        WHERE nombre LIKE '%$textoBusqueda%'
				        OR descripcion LIKE '%$textoBusqueda%'
				        OR precio LIKE '%$textoBusqueda%'
				        ORDER BY nombre");
		    if ($result) {
                return $result;
            } else {
                return null;
            }

        }

        public function update() {
            // Primero, recuperamos todos los datos del formulario
            $id = $_REQUEST["id"];
            $nombre = $_REQUEST["nombre"];
            $descripcion = $_REQUEST["descripcion"];
            $imagen = $_REQUEST["imagen"];
            $precio = $_REQUEST["precio"];
      
            $result = $this->db->manipulacion("UPDATE instalacion SET
                                nombre = '$nombre',
								descripcion = '$descripcion',
								imagen = '$imagen',
								precio = '$precio'
                                WHERE id = '$id'");
            return $result;
        }

    }