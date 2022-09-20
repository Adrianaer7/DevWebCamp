<?php 
    namespace Controllers;

    use Model\Ponente;
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
            $alertas = [];
            $ponente = new Ponente;

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $ponente->sincronizar($_POST);
                
                //Validar
                $alertas = $ponente->validar();
            }

            $router->render("admin/ponentes/crear", [
                "titulo" => $titulo,
                "alertas" => $alertas,
                "ponente" => $ponente
            ]);
        }
    }

?>