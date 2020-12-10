<?php
    class Seguridad {

        public function abrirSesion($usuario) {
            $_SESSION["idUsuario"] = $usuario[0]->id;
            $_SESSION["emailUsuario"] = $usuario[0]->email;
            $_SESSION["nombreUsuario"] = $usuario[0]->nombre;
            $_SESSION["fotografiaUsuario"] = $usuario[0]->imagen;
            $_SESSION["tipoUsuario"] = $usuario[0]->tipo;
        }

        public function cerrarSesion() {
            session_destroy();
        }

        public function get($variable) {
            return $_SESSION[$variable];
        }

        public function haySesionIniciada() {
            if (isset($_SESSION["idUsuario"])) {
                return true;
            } else {
                return false;
            }
        }

        public function errorAccesoNoPermitido() {
			$data['msjError'] = "No tienes permisos para hacer eso";
			$this->vista->mostrar("usuario/formularioLogin", $data);
        }
    }