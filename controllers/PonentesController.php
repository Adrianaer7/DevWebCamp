<?php 
    namespace Controllers;

    use MVC\Router;

    class PonentesController {
        
        public static function index(Router $router) {
            $titulo = "Ponentes / Conferencistas";

            $router->render("admin/ponentes/index", [
                "titulo" => $titulo
            ]);
        }

        public static function crear(Router $router) {
            $titulo = "Registrar Ponente";

            $router->render("admin/ponentes/crear", [
                "titulo" => $titulo
            ]);
        }
    }

?>