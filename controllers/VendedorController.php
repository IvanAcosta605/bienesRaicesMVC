<?php
    
    namespace Controllers;

    use MVC\Router;
    use Model\Vendedor;

    class vendedorController{
        public static function crear(Router $router){
            $vendedor= new Vendedor;
            //Arreglo con mensaje de errores
            $errores = Vendedor::getErrores();

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                //Crear una nueva instancia
                $vendedor = new Vendedor($_POST['vendedor']);
        
                //Validar que no haya campos vacios
                $errores = $vendedor->validar();
        
                //No hay errores
                if(empty($errores)){
                    $vendedor->guardar();
                }
            }

            $router->render('vendedores/crear', [
                'vendedor' => $vendedor,
                'errores' => $errores
            ]);
        }

        public static function actualizar(Router $router){

            $id = validarORedireccionar('/admin');

            //Obtener los datos del vendedor
            $vendedor = Vendedor::find($id);

            //Arreglo con mensaje de errores
            $errores = Vendedor::getErrores();

            if($_SERVER['REQUEST_METHOD'] === 'POST'){

                //Asugnar los atributos
                $args = $_POST['vendedor'];
        
                //Sincronizar objeto en memoria
                $vendedor->sincronizar($args);
        
                //Validacion
                $errores = $vendedor->validar();
        
                //Revisar que el array de errores este vacio
                if(empty($errores)){
                    $vendedor->guardar();
                }
            }

            $router->render('vendedores/actualizar', [
                'vendedor' => $vendedor,
                'errores' => $errores
            ]);
        }

        public static function eliminar(){

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                //Validar ID
                $id = $_POST['id'];
                $id = filter_var($id, FILTER_VALIDATE_INT);

                if($id){
                    $tipo = $_POST['tipo'];
                    if(validarTipoContenido($tipo)){
                        $vendedor=Vendedor::find($id);
                        $vendedor->eliminar();
                    }  
                }
            }
        }
    }