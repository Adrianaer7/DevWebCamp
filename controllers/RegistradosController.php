<?php 

    namespace Controllers;

    use MVC\Router;
    use Classes\Paginacion;
use Model\Paquete;
use Model\Registro;
use Model\Usuario;

    class RegistradosController {
        
        public static function index(Router $router) {
            $titulo = "Usuarios registados";

            //Si no soy administrador, no puedo acceder al listado de registros
            if(!is_admin()) {
                header("Location: /login");
            }

            //Validar paginacion de url
            $pagina_actual = $_GET["page"];
            $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
            if(!$pagina_actual || $pagina_actual < 1) {
                header("Location: /admin/registrados?page=1"); //redirecciono a la primera pagina. De paso se le asigna $pagina_actual = 1 
            }
            $registros_por_pagina = 10;
            //Obtener total de regisros
            $total = Registro::total();

            //Creo lo necesario para paginar
            $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

            //si en la url ingreso un numero mayor a las paginas calculadas por $total_paginas()
            if($pagina_actual > $paginacion->total_paginas()) {
                header("Location: /admin/registrados?page=1");

            }

            //Traigo la cantidad de registros que quiero desde la bd
            $registros = Registro::paginar($registros_por_pagina, $paginacion->offset());
            
            //Cruzo los datos
            foreach ($registros as $registro) {
                $registro->usuario = Usuario::find($registro->usuario_id);
                $registro->paquete = Paquete::find($registro->paquete_id);
            }

            $router->render("admin/registrados/index", [
                "titulo" => $titulo,
                "registros" => $registros,
                "paginacion" => $paginacion->paginacion()
            ]);
            
            
        }
    }

?>