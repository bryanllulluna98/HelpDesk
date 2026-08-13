<?php
    session_start();

    class Conectar{

        protected function conexion(){
            try {
                $conexion = $this->dbh = new PDO("mysql:host=localhost;dbname=helpdesk_tecnostar", "root", "");
                return $conexion;
            } catch (Exception $e) {
                print "¡Error!: " . $e->getMessage() . "<br/>";
                die();
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