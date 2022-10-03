<?php

    namespace Controllers;

    use MVC\Router;
    use Model\Evento;
    use Model\Dia;
    use Model\Hora;
    use Model\Ponente;
    use Model\Categoria;

    class PaginasController {
        public static function index(Router $router) {
            $titulo = "Inicio";

            echo "inicio";

            $router->render("paginas/index", [
                "titulo" => $titulo
            ]);
        }

        public static function evento(Router $router) {
            $titulo = "Sobre DevWebCamp";


            $router->render("paginas/devwebcamp", [
                "titulo" => $titulo
            ]);
        }

        public static function paquetes(Router $router) {
            $titulo = "Paquetes DevWebCamp";
            
            echo "paquetes";

            $router->render("paginas/paquetes", [
                "titulo" => $titulo
            ]);
        }

        public static function conferencias(Router $router) {
            $titulo = "Conferencias & Workshops";

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
            
            
            $router->render("paginas/conferencias", [
                "titulo" => $titulo,
                "eventos" => $eventos_formateados
            ]);
        }
    }