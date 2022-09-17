<?php 
    namespace Controllers;

    use MVC\Router;

    class DashboardController {
        
        public static function index(Router $router) {
            $titulo = "Panel de administración";

            $router->render("admin/dashboard/index", [
                "titulo" => $titulo
            ]);
        }
    }

?>