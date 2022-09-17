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
    }

?>