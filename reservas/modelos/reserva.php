<?php
    include_once("DB.php");

    class Reserva {
        /**
         * Constructor. Establece la conexión con la BD y la guarda
         * en una variable de la clase
         */
        public function __construct() {
            $this->db = new DB();
        }

        public function get($id) {
            if ($result = $this->db->query("SELECT * FROM reserva WHERE id = '$id'")) {
                return $result;
            } else {
                return null;
            }
        }

        public function getAll() {
            if ($result = $this->db->consulta("SELECT * FROM reserva")) {
                return $result;
            } else {
                return null;
            }
        }

        public function getReservasUsuario($idUsuario){
            $result = $this->db->consulta("SELECT reserva.fecha, reserva.hora, reserva.precio FROM reserva INNER JOIN usuario ON reserva.idUsuario = usuario.id WHERE usuario.id = '$idUsuario'");
        }

        public function delete($id){
            $result = $this->db->manipulacion("DELETE FROM reserva WHERE id = '$id'");
            return $result;
        }

        /** 
        * Realiza una búsqueda de la reserva
        * @param textoBusqueda El texto de búsqueda
        * @return Un array de objetos con los datos de las reservas encontradas
        */
        public function busquedaAproximada($textoBusqueda) {
            // Buscamos las reservas que coincidan con el texto de búsqueda
            $result = $this->db->consulta("SELECT * FROM reserva
				        WHERE fecha LIKE '%$textoBusqueda%'
				        OR hora LIKE '%$textoBusqueda%'
				        OR precio LIKE '%$textoBusqueda%'
				        ORDER BY fecha");
		    if ($result) {
                return $result;
            } else {
                return null;
            }

        }
    }