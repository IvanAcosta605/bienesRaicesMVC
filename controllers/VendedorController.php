<?php
    
    namespace Controllers;

    use MVC\Router;
    use Model\Vendedor;

    class vendedorController{
        public static function crear(Router $router){
            $vendedor= new Vendedor;
            //Arreglo con mensaje de errores
            $errores = Vendedor::getErrores();

            $router->render('vendedores/crear', [
                'vendedor' => $vendedor,
                'errores' => $errores
            ]);
        }

        public static function actualizar(){
            echo "Actualizar Vendedor";
        }

        public static function eliminar(){
            echo "Eliminar Vendedor";
        }
    }