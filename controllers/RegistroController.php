<?php 
    namespace Controllers;

    use MVC\Router;
    use Model\Regalo;
    use Model\Registro;
    use Model\Usuario;
    use Model\Paquete;
    use Model\Evento;
    use Model\Dia;
    use Model\Hora;
    use Model\Ponente;
    use Model\Categoria;
    use Model\EventosRegistros;

    class RegistroController {

        public static function crear(Router $router) {
            $titulo = "Finalizar Registro";

            if(!is_auth()) {
                header("Location: /");
            }

            //Si el usuario ya tiene un registro de plan de pago hecho, redirijo a su boleto
            $registro = Registro::where("usuario_id", $_SESSION["id"]);
            if(isset($registro) && ($registro->paquete_id === "3" || $registro->paquete_id === "2")) {
                header("Location: /boleto?id=" . urlencode($registro->token));
                return;
            }

            //Si el usuario pagó el plan presencial, que elija los eventos
            if($registro->paquete_id === "1") {
                header("Location: /finalizar-registro/conferencias");
                return;
            }

            $router->render("registro/crear", [
                "titulo" => $titulo
            ]);
        }

        public static function gratis () {
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                if(!is_auth()) {
                    header("Location: /login");
                    return;
                }

                //Si el usuario ya tiene un registro de plan hecho y quiere agregar uno mas, redirijo
                $registro = Registro::where("usuario_id", $_SESSION["id"]);
                if(isset($registro) && $registro->paquete_id === "3") {
                    header("Location: /boleto?id=" . urlencode($registro->token));
                    return;
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
                    return;
                }
            }
        }

        public static function pagar () {
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                if(!is_auth()) {
                    header("Location: /login");
                    return;
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
                return;
            }

            //Validar el token
            $registro = Registro::where("token", $id);
            if(!$registro) {
                header("Location: /");
                return;
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
                return;
            }

            $usuario_id = $_SESSION["id"];

            $registro = Registro::where("usuario_id", $usuario_id);

            //Redireccionar a boleto virtual en caso de haber finalizado su registro de pago virtual
            if(isset($registro) && $registro->paquete_id === "2") {
                header("Location: /boleto?id=" . urlencode($registro->token));
                return;
            }
            //Redireccionar en caso que no se haya pagado el plan presencial
            if(!$registro || $registro->paquete_id !== "1") {
                header("Location: /");
                return;
            }

            //Redireccionar a boleto virtual en caso de haber finalizado su registro de pago presencial
            if(isset($registro->regalo_id) && $registro->paquete_id === "1") {
                header("Location: /boleto?id=" . urlencode($registro->token));
                return;
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

            //Manejando el registro $_POST
            if($_SERVER["REQUEST_METHOD"] === "POST") {

                if(!is_auth()) {
                    header("Location: /login");
                }

                //Validar que exista evento seleccionado
                $eventos = explode(",", $_POST["eventos"]); //guardo las id de los eventos seleccionados en un array separados por coma. Esto viene del formdata del registro.js
                if(empty($eventos)) {   //en caso de que el usuario consiga enviar el formulario sin eventos seleccionados
                    echo json_encode(["resultado" => false]);
                    return;
                }

                //Obtener el registro del usuario
                $registro = Registro::where("usuario_id", $_SESSION["id"]);
                if(!isset($registro) || $registro->paquete_id !== "1") {   //en caso de que el usuario que no pagó el plan presencial o no tiene un pago hecho. Esto se hace por las dudas si el usuario llegó  hasta aca de alguna manera no valida 
                    echo json_encode(["resultado" => false]);
                    return;
                }

                $eventos_array = [];    //para guardar los eventos con disponibilidad 
                //Validar la disponibilidad de los eventos seleccionados
                foreach($eventos as $evento_id) {
                    $evento = Evento::find($evento_id); //busco un evento por id
                    if(!isset($evento) || $evento->disponibles === "0") {   //si no existe el evento o no hay cupos disponibles
                        echo json_encode(["resultado" => false]);
                        return;
                    }

                    $eventos_array[] = $evento;
                }

                foreach($eventos_array as $evento) {
                    //Descontar disponibles del objeto en memoria y guardar en bd
                    $evento->disponibles -= 1;
                    $evento->guardar();

                    //Almacenar el registro
                    $datos = [
                        "evento_id" => (int) $evento->id,
                        "registro_id" => (int) $registro->id
                    ];

                    //Guardo en la bd los conferencias elegidas por el usuario
                    $registro_usuario = new EventosRegistros($datos);
                    $registro_usuario->guardar();

                    //Almacenar el regalo
                    $registro->sincronizar(["regalo_id" => $_POST["regalo_id"]]);   //al objeto en memoria registro le modifico solo su regalo_id
                    $resultado = $registro->guardar();

                    if($resultado) {
                        echo json_encode([
                            "resultado" => $resultado,
                            "token" => $registro->token
                        ]);
                    } else {
                        echo json_encode(["resultado" => false]);
                    }

                    return;
                }
            }

            $router->render("registro/conferencias", [
                "titulo" => $titulo,
                "registro" => $registro,
                "eventos" => $eventos_formateados,
                "regalos" => $regalos
            ]);
        }
    }
?>