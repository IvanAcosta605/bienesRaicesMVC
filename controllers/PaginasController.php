<?php
    namespace Controllers;

    use MVC\Router;
    use Model\Propiedad;

    class PaginasController{
        public static function index(Router $router){

            $inicio = true;

            $propiedades = Propiedad::get(3);

            $router->render('paginas/index', [
                'inicio' => $inicio,
                'propiedades' => $propiedades
            ]);
        }

        public static function nosotros(Router $router){
            $router->render('paginas/nosotros', []);
        }

        public static function propiedades(){
            echo "Desde propiedades";
        }

        public static function propiedad(){
            echo "Desde propiedad";
        }

        public static function blog(){
            echo "Desde blog";
        }

        public static function entrada(){
            echo "Desde entrada";
        }

        public static function contacto(){
            echo "Desde contacto";
        }
    }