<?php

    namespace Model;

    class Admin extends ActiveRecord{
        //Base de datos
        protected static $tabla = 'usuarios';
        protected static $columnasDB = ['id', 'email', 'password'];
        
        public $id;
        public $email;
        public $password;

        public function __construct($args = []){

            $this->id = $args['id'] ?? null;
            $this->email = $args['email'] ?? '';
            $this->password = $args['password'] ?? '';
        }

        public function validar(){

            if(!$this->email){
                self::$errores[] = 'El E-Mail es Obligatorio';
            }

            if(!$this->password){
                self::$errores[] = 'El Password es Obligatorio';
            }

            return self::$errores;
        }

        public function existeUsuario(){
            //Revisar si el usuario existe
            $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

            $resultado = self::$db->query($query);

            if(!$resultado->num_rows){
                self::$errores[] = 'El Usuario no Existe';
                return;
            }
        }

    }