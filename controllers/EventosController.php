<?php 
    namespace Controllers;

    use Classes\Paginacion;
    use Model\Categoria;
    use Model\Dia;
    use Model\Evento;
    use Model\Hora;
    use Model\Ponente;
    use MVC\Router;

    class EventosController {
        
        public static function index(Router $router) {
            $titulo = "Conferencias y Workshops";

            if(!is_admin()) {
                header("Location: /login");
            }
            
            //Valido URL
            $pagina_actual = $_GET["page"];
            $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
            if(!$pagina_actual || $pagina_actual < 1) {
                header("Location: /admin/eventos?page=1");
            }

            //Defino cuantos quiero mostrar por pagina
            $por_pagina = 10;

            //Traigo la cantidad de eventos creados
            $total = Evento::total();

            //Creo lo necesario para paginar
            $paginacion = new Paginacion($pagina_actual, $por_pagina, $total);

            if($pagina_actual > $paginacion->total_paginas()) {
                header("Location: /admin/eventos?page=1");
            }

            //Traigo todos los registros que quiero desde la bd
            $eventos = Evento::paginar($por_pagina, $paginacion->offset());

            //Cruzo la informacion de los modelos
            foreach($eventos as $evento) {
                //creo una propiedad "categoria" dentro de la clase evento que contiene el id y nombre, en la cual la id es igual a la de propiedad categoria_id
                $evento->categoria = Categoria::find($evento->categoria_id);
                $evento->dia = Dia::find($evento->dia_id);
                $evento->hora = Hora::find($evento->hora_id);
                $evento->ponente = Ponente::find($evento->ponente_id);
            }
            

            $router->render("admin/eventos/index", [
                "titulo" => $titulo,
                "eventos" => $eventos,
                "paginacion" => $paginacion->paginacion()
            ]);
        }

        public static function crear(Router $router) {
            $titulo = "Registrar evento";
            $alertas = [];
            
            if(!is_admin()) {
                header("Location: /login");
            }

            $evento = new Evento;
            $categorias = Categoria::all("ASC");
            $dias = Dia::all("ASC");
            $horas = Hora::all("ASC");

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $evento = new Evento($_POST);
                $alertas = $evento->validar();

                if(empty($alertas)) {
                    $resultado = $evento->guardar();

                    if($resultado) {
                        header("Location: /admin/eventos");
                    }
                }
            }

            $router->render("admin/eventos/crear", [
                "titulo" => $titulo,
                "alertas" => $alertas,
                "categorias" => $categorias,
                "dias" => $dias,
                "horas" => $horas,
                "evento" => $evento
            ]);
        }

        public static function editar(Router $router) {
            $titulo = "Editar evento";
            $alertas = [];

            if(!is_admin()) {
                header("Location: /login");
            }
            
            $id = $_GET["id"];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id) {
                header("Location: /admin/eventos");
            }

            $categorias = Categoria::all("ASC");
            $dias = Dia::all("ASC");
            $horas = Hora::all("ASC");

            $evento = Evento::find($id);
            if(!$evento) {
                header("Location: /admin/eventos");
            }
            
            if($_SERVER["REQUEST_METHOD"] === "POST") {

                $evento->sincronizar($_POST);

                $alertas = $evento->validar();

                if(empty($alertas)) {
                    $resultado = $evento->guardar();

                    if($resultado) {
                        header("Location: /admin/eventos");
                    }
                }
            }

            $router->render("admin/eventos/editar", [
                "titulo" => $titulo,
                "alertas" => $alertas,
                "categorias" => $categorias,
                "dias" => $dias,
                "horas" => $horas,
                "evento" => $evento
            ]);
        }

        public static function eliminar() {
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                if(!is_admin()) {
                    header("Location: /login");
                }

                $id = $_POST["id"];
                $evento = Evento::find($id);
                
                if(isset($evento)) {
                    $resultado = $evento->eliminar();
                    if($resultado) {
                        header("Location: /admin/eventos");    //redirecciono para que se refresque la pantalla con el listado actualizado
                    }
                } else {
                    header("Location: /admin/eventos");
                }
            }
        }
    }

?>