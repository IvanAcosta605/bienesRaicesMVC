<?php

    namespace Controllers;

    use MVC\Router;
    use Model\Propiedad;
    use PHPMailer\PHPMailer\PHPMailer;

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
            $router->render('paginas/nosotros');
        }

        public static function propiedades(Router $router){

            $propiedades = Propiedad::all();

            $router->render('paginas/propiedades', [
                'propiedades' => $propiedades
            ]);
        }

        public static function propiedad(Router $router){

            $id = validarORedireccionar('/propiedades');

            //Obtener los datos de la propiedad
            $propiedad = Propiedad::find($id);

            $router->render('paginas/propiedad', [
                'propiedad' => $propiedad
            ]);
        }

        public static function blog(Router $router){
            $router->render('paginas/blog');
        }

        public static function entrada(Router $router){
            $router->render('paginas/entrada');
        }

        public static function contacto(Router $router){

            if($_SERVER['REQUEST_METHOD'] === 'POST'){

                $respuestas = $_POST['contacto'];

                //Crear una instancia de PHPMailer
                $phpmailer = new PHPMailer();
                //Configurar SMTP
                $phpmailer->isSMTP();
                $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
                $phpmailer->SMTPAuth = true;
                $phpmailer->Port = 2525;
                $phpmailer->Username = 'b52b6391cb0db3';
                $phpmailer->Password = '7f20d5a0a3065f';
                //$phpmailer->SMTPSecure = 'tls';

                //Configurar el contenido del mail
                $phpmailer->setFrom('admin@bienesraices.com');
                $phpmailer->addAddress('admin@bienesraices.com', 'BienesRaices.com');
                $phpmailer->Subject = '¡Tienes un Nuevo Mensaje!';

                //Habilitar HTML
                $phpmailer->isHTML(true);
                $phpmailer->CharSet = 'UTF-8';

                //Definir el contenido
                $contenido = '<html>';
                $contenido .= '<p>Tienes un Nuevo Nensaje</p>';
                $contenido .= ' <p>Nombre: ' . $respuestas['nombre'] . '</p>';
                $contenido .= ' <p>Email: ' . $respuestas['email'] . '</p>';
                $contenido .= ' <p>Telefono: ' . $respuestas['telefono'] . '</p>';
                $contenido .= ' <p>Mensaje: ' . $respuestas['mensaje'] . '</p>';
                $contenido .= ' <p>Vende o Compra: ' . $respuestas['tipo'] . '</p>';
                $contenido .= ' <p>Precio o Presupuesto: G$' . $respuestas['precio'] . '</p>';
                $contenido .= ' <p>Contactar Mediante: ' . $respuestas['contacto'] . '</p>';
                $contenido .= ' <p>Fecha Contacto: ' . $respuestas['fecha'] . '</p>';
                $contenido .= ' <p>Hora: ' . $respuestas['hora'] . '</p>';
                $contenido .= '</html>';



                //$mail->Subject = 'Here is the subject';
                $phpmailer->Body    = $contenido;
                $phpmailer->AltBody = 'Esto es texto alternativo sin HTML';

                //Enviar el mail
                if($phpmailer->send()){
                    echo "Mensaje enviado correctamente";
                }else{
                    echo "El mensaje no se pudo enviar...";
                }

            }

            $router->render('paginas/contacto', [
                
            ]);
        }
    }