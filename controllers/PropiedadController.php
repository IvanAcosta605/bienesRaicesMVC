<?php

    namespace Controllers;

    use MVC\Router;
    use Model\Propiedad;
    use Model\Vendedor;
    use Intervention\Image\ImageManager as Image;
    use Intervention\Image\Drivers\Gd\Driver;

    class PropiedadController{

        public static function index(Router $router){
            //Consulta para obtener todas las propiedades
            $propiedades = Propiedad::all();
            //Consulta para obtener todos los vendedores
            $vendedores = Vendedor::all();
            //Muestra un mensaje condicional
            $resultado = $_GET['resultado'] ?? null;

            $router->render('propiedades/admin', [
                'propiedades' => $propiedades,
                'resultado' => $resultado,
                'vendedores' => $vendedores
            ]);

        }

        public static function crear(Router $router){
            $propiedad = new Propiedad;
            //Consulta para obtener todos los vendedores
            $vendedores = Vendedor::all();
            //Arreglo con mensaje de errores
            $errores = Propiedad::getErrores();

            if($_SERVER['REQUEST_METHOD'] === 'POST'){

                /**Crea una nueva instancia */
                $propiedad = new Propiedad($_POST['propiedad']);
        
                /** SUBIDA DE ARCHIVOS */
        
                //Generar nombre unico
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        
                //Setear la imagen
                //Realiza un resize a la imagen con intervention version 3.4
                if($_FILES['propiedad']['tmp_name']['imagen']){
                    $manager = new Image(Driver::class);
                    $image = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800,600);  
                    $propiedad->setImagen($nombreImagen);
                }
        
                //Validar
                $errores = $propiedad->validar();
        
                //Revisar que el array de errores este vacio
                if(empty($errores)){
        
                    //Crear carpeta
                    if(!is_dir(CARPETA_IMAGENES)){
                        mkdir(CARPETA_IMAGENES);
                    }
        
                    //Guarda la imagen en el servidor
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                    
                    // Guarda en la base de datod
                    $propiedad->guardar();
                }
            }

            $router->render('propiedades/crear', [
                'propiedad' => $propiedad,
                'vendedores' => $vendedores,
                'errores' => $errores
            ]);

        }

        public static function actualizar(Router $router){

            $id = validarORedireccionar('/admin');

            //Obtener los datos de la propiedad
            $propiedad = Propiedad::find($id);

            //Consultar para obtener los vendedores
            $vendedores = Vendedor::all();

            //Arreglo con mensaje de errores
            $errores = Propiedad::getErrores();

            //Ejecutar el codigo despues de que el usuario envia el formulario
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                //Asugnar los atributos
                $args = $_POST['propiedad'];

                $propiedad->sincronizar($args);

                //Validacion
                $errores = $propiedad->validar();

                /** SUBIDA DE ARCHIVOS */

                //Generar nombre unico
                $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

                //Setear la imagen
                //Realiza un resize a la imagen con intervention version 3.4
                if($_FILES['propiedad']['tmp_name']['imagen']){
                    $manager = new Image(Driver::class);
                    $image = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800,600);  
                    $propiedad->setImagen($nombreImagen);
                }

                //Revisar que el array de errores este vacio
                if(empty($errores)){
                    if($_FILES['propiedad']['tmp_name']['imagen']){
                        //Almacenar imagen
                        $image->save(CARPETA_IMAGENES . $nombreImagen);
                    }
                    $propiedad->guardar();
                }
            }

            $router->render('propiedades/actualizar', [
                'propiedad' => $propiedad,
                'vendedores' => $vendedores,
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
                        $propiedad=Propiedad::find($id);
                        $propiedad->eliminar();
                    } 
                }
            }
        }
    }