<?php
    session_start();

    class Conectar{

        protected function conexion(){
            try {
                $conexion = new PDO("mysql:host=localhost;dbname=helpdesk_tecnostar", "root", "");
                $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                return $conexion;
            } catch (PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }

        protected function set_names(){
            return $this->conexion()->query("SET NAMES 'utf8'");
        }

        public function ruta(){
            return "http://localhost/PERSONAL_HelpDesk/";
        }
    }
?>