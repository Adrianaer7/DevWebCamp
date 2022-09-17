<?php 
    namespace Controllers;

    use MVC\Router;

    class RegistradosController {
        
        public static function index(Router $router) {
            $titulo = "Usuarios registados";

            $router->render("admin/registrados/index", [
                "titulo" => $titulo
            ]);
        }
    }

?>