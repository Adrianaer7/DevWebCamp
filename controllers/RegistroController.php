<?php 



    namespace Controllers;

    use Model\Registro;
    use Model\Usuario;
    use MVC\Router;
    use Model\Paquete;
    use Model\Evento;
    use Model\Dia;
    use Model\Hora;
    use Model\Ponente;
    use Model\Categoria;
use Model\Regalo;

    class RegistroController {

        public static function crear(Router $router) {
            $titulo = "Finalizar Registro";

            if(!is_auth()) {
                header("Location: /");
            }

            //Si el usuario ya tiene un registro de plan hecho, redirijo a su boleto
            $registro = Registro::where("usuario_id", $_SESSION["id"]);
            if(isset($registro) && $registro->paquete_id === "3") {
                header("Location: /boleto?id=" . urlencode($registro->token));
            }

            $router->render("registro/crear", [
                "titulo" => $titulo
            ]);
        }

        public static function gratis () {
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                if(!is_auth()) {
                    header("Location: /login");
                }

                //Si el usuario ya tiene un registro de plan hecho y quiere agregar uno mas, redirijo
                $registro = Registro::where("usuario_id", $_SESSION["id"]);
                if(isset($registro) && $registro->paquete_id === "3") {
                    header("Location: /boleto?id=" . urlencode($registro->token));
                }

                //Creo un token aleatorio que contenga hasta 8 caracteres empezando del primer caracter hasta el 8vo
                $token = substr(md5(uniqid(rand(), true)), 0, 8);

                //Guardar registro
                $datos = [
                    "paquete_id" => 3,
                    "pago_id" => "",
                    "token" => $token,
                    "usuario_id" => $_SESSION["id"]
                ];
                $registro = new Registro($datos);
                $resultado = $registro->guardar();

                //Redirigir
                if($resultado) {
                    header("Location: /boleto?id=" . urlencode($registro->token));  //urlencode evita caracteres especiales
                }
            }
        }

        public static function pagar () {
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                if(!is_auth()) {
                    header("Location: /login");
                }

                //Validar que POST no venga vacio
                if(empty($_POST)) {
                    echo json_encode([]);
                    return;
                }


                //Creo el registro con los datos del pago
                $datos = $_POST;
                $datos["token"] = substr(md5(uniqid(rand(), true)), 0, 8);
                $datos["usuario_id"] = $_SESSION["id"];
                //debugear($datos); puedo acceder a estos datos yendo al navegador->herramientas de desarrollador->red->fetch/XHR->pagar->vista previa
                
                //Guardo el registro en la BD
                try {
                    $registro = new Registro($datos);
                    $resultado = $registro->guardar();
                    echo json_encode($resultado);   //true si sale todo bien. envio esta respuesta json para obtenerla en el script de pago
                } catch (\Throwable $th) {
                    echo json_encode(["resultado" => "error"]); //devuelvo error si no sale bien
                }
            }
        }

        public static function boleto(Router $router) {
            $titulo = "Asistencia a DevWebCamp";

            //Validar la id del usuario
            $id = $_GET["id"];
            if(!$id || !strlen($id) === 8)  {   //si no existe el usuario o el token de la url tiene un largo distinto a 8 caracteres
                header("Location: /");
            }

            //Validar el token
            $registro = Registro::where("token", $id);
            if(!$registro) {
                header("Location: /");
            }

            //Llenar las tablas de referencia - Cruzar la informacion de los modelos
            $registro->usuario = Usuario::find($registro->usuario_id);
            $registro->paquete = Paquete::find($registro->paquete_id);


            $router->render("registro/boleto", [
                "titulo" => $titulo,
                "registro" => $registro
            ]);
        }

        public static function conferencias(Router $router) {
            $titulo = "Elige Workshops y Conferencias";

            if(!is_auth()) {
                header("Location: /login");
            }

            $usuario_id = $_SESSION["id"];

            $registro = Registro::where("usuario_id", $usuario_id);
            if(!$registro || $registro->paquete_id !== "1") {
                header("Location: /");
            }
            
            //Traigo todos los eventos, ordenados desde la primera hora hasta la ultima
            $eventos = Evento::ordenar("hora_id", "ASC");

            $eventos_formateados = [];

            //Recorro el array de todos los eventos ordenados por hora
            foreach($eventos as $evento) {
                //creo una propiedad dentro del objeto evento en la cual la id de la propiedad del evento es igual a la que hay en el modelo consultado
                $evento->categoria = Categoria::find($evento->categoria_id);
                $evento->dia = Dia::find($evento->dia_id);
                $evento->hora = Hora::find($evento->hora_id);
                $evento->ponente = Ponente::find($evento->ponente_id);
                
                if($evento->dia_id === "1" && $evento->categoria_id === "1") {
                    $eventos_formateados["conferencias_v"][] = $evento;
                }
                if($evento->dia_id === "2" && $evento->categoria_id === "1") {
                    $eventos_formateados["conferencias_s"][] = $evento;
                }
                if($evento->dia_id === "1" && $evento->categoria_id === "2") {
                    $eventos_formateados["workshops_v"][] = $evento;
                }
                if($evento->dia_id === "2" && $evento->categoria_id === "2") {
                    $eventos_formateados["workshops_s"][] = $evento;
                }
            }

            $regalos = Regalo::all("ASC");

            $router->render("registro/conferencias", [
                "titulo" => $titulo,
                "registro" => $registro,
                "eventos" => $eventos_formateados,
                "regalos" => $regalos
            ]);
        }
    }
?>