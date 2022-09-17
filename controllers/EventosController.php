<?php 
    namespace Controllers;

    use MVC\Router;

    class EventosController {
        
        public static function index(Router $router) {
            $titulo = "Conferencias y Workshops";

            $router->render("admin/eventos/index", [
                "titulo" => $titulo
            ]);
        }
    }

?>