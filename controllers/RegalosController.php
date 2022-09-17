<?php 
    namespace Controllers;

    use MVC\Router;

    class RegalosController {
        
        public static function index(Router $router) {
            $titulo = "Regalos";

            $router->render("admin/regalos/index", [
                "titulo" => $titulo
            ]);
        }
    }

?>