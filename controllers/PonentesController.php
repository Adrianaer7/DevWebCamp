<?php 

    namespace Controllers;

    use Classes\Paginacion;
    use Model\Ponente;
    use MVC\Router;
    use Intervention\Image\ImageManagerStatic as Image;

    class PonentesController {
        
        public static function index(Router $router) {
            $titulo = "Ponentes / Conferencistas";
            
            //Si no soy administrador, no puedo acceder al listado de ponentes
            if(!is_admin()) {
                header("Location: /login");
            }

            //Validar paginacion de url
            $pagina_actual = $_GET["page"];
            $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
            if(!$pagina_actual || $pagina_actual < 1) {
                header("Location: /admin/ponentes?page=1"); //redirecciono a la primera pagina. De paso se le asigna $pagina_actual = 1 
            }
            $registros_por_pagina = 10;
            //Obtener total de regisros
            $total = Ponente::total();
        
            //Creo lo necesario para paginar
            $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

            //si en la url ingreso un numero mayor a las paginas calculadas por $total_paginas()
            if($pagina_actual > $paginacion->total_paginas()) {
                header("Location: /admin/ponentes?page=1");

            }

            //Traigo la cantidad de registros que quiero desde la bd
            $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());
            
            $router->render("admin/ponentes/index", [
                "titulo" => $titulo,
                "ponentes" => $ponentes,
                "paginacion" => $paginacion->paginacion()
            ]);
        }

        public static function crear(Router $router) {
            $titulo = "Registrar Ponente";
            $alertas = [];
            $ponente = new Ponente;

            if(!is_admin()) {
                header("Location: /login");
            }

            if($_SERVER["REQUEST_METHOD"] === "POST") {

                if(!empty($_FILES["imagen"]["tmp_name"])) {
                    $carpeta_imagenes = "../public/img/speakers";

                    //Crear la carpeta si no existe
                    if(!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);    //le paso la ruta, el codigo para permitir ciertos permisos, y el permiso para crear subdirectorios sobre la ruta especiifcada
                    }

                    //dejo estas imagenes en memoria
                    $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("png", 80);  //guardo la imagen en 800px con formato png y en calidad 80%. Intervention Image no soporta avif asi que lo hago a png y webp
                    $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("webp", 80);

                    //Creo un nombre para las imagenes
                    $nombre_imagen = md5(uniqid(rand(), true));

                    //Agrego el nombre de la imagen al formulario
                    $_POST["imagen"] = $nombre_imagen;
                }

                //Con enconde convierto el array redes a un string, y con json_unescaped quito las barras invertidas que crea el encode. Hago esto porque el metodo sanitizar atributos en activeRecord no acepta array, solo string
                $_POST["redes"] = json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);

                //Envio al objeto en memoria el $POST con el nombre de la imagen cambiada y las redes en forma de string
                $ponente = new Ponente($_POST);

                //Validar
                $alertas = $ponente->validar();

                //Guardar el registro
                if(empty($alertas)) {
                    //Guardar las imagenes
                    $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");

                    //Guardar en la BD
                    $resultado = $ponente->guardar();

                    if($resultado) {
                        header("Location: /admin/ponentes");
                    }
                }
            }

            $router->render("admin/ponentes/crear", [
                "titulo" => $titulo,
                "alertas" => $alertas,
                "ponente" => $ponente,
                "redes" => json_decode($ponente->redes) //Al string de lo convierto a objeto para que tenga el mismo nombre "redes" el value del input tanto al crear como al editar
            ]);
        }

        public static function editar(Router $router) {
            $titulo = "Editar Ponente";
            $alertas = [];

            if(!is_admin()) {
                header("Location: /login");
            }

            //Verificar si el id es entero
            $id = $_GET["id"];
            $id = filter_var($id, FILTER_VALIDATE_INT);
            if(!$id) {
                header("Location: /admin/ponentes");
            }

            //Obtener ponente a editar
            $ponente = Ponente::find($id);
            //Verificar si el id existe en la bd
            if(!$ponente) {
                header("Location: /admin/ponentes");
            }

            //Creo una variable temporal para usarla en el formulario.html
            $ponente->imagen_actual = $ponente->imagen;

            if($_SERVER["REQUEST_METHOD"] === "POST") {
                //Si cargué una imagen reciente
                if(!empty($_FILES["imagen"]["tmp_name"])) {
                    $carpeta_imagenes = "../public/img/speakers";

                    //Crear la carpeta si no existe
                    if(!is_dir($carpeta_imagenes)) {
                        mkdir($carpeta_imagenes, 0755, true);    //le paso la ruta, el codigo para permitir ciertos permisos, y el permiso para crear subdirectorios sobre la ruta especiifcada
                    }

                    //dejo estas imagenes en memoria
                    $imagen_png = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("png", 80);  //guardo la imagen en 800px con formato png y en calidad 80%. Intervention Image no soporta avif asi que lo hago a png y webp
                    $imagen_webp = Image::make($_FILES["imagen"]["tmp_name"])->fit(800,800)->encode("webp", 80);

                    //Creo un nombre para las imagenes
                    $nombre_imagen = md5(uniqid(rand(), true));

                    //Agrego el nombre de la imagen al formulario
                    $_POST["imagen"] = $nombre_imagen;
                } else {
                    //Si no cargué imagen, le pongo al formulario la que viene de la bd
                    $_POST["imagen"] = $ponente->imagen_actual;
                }

                $_POST["redes"] = json_encode($_POST["redes"], JSON_UNESCAPED_SLASHES);

                $ponente->sincronizar($_POST);

                $alertas = $ponente->validar();

                //Guardar el registro editado
                if(empty($alertas)) {
                    if(isset($nombre_imagen)) { //Si existe es porque se subio una imagen nueva
                        //Guardar las imagenes
                        $imagen_png->save($carpeta_imagenes . "/" . $nombre_imagen . ".png");
                        $imagen_webp->save($carpeta_imagenes . "/" . $nombre_imagen . ".webp");
    
                    }
                    //Guardar en la BD
                    $resultado = $ponente->guardar();

                    if($resultado) {
                        header("Location: /admin/ponentes");
                    }
                }
            }

            $router->render("admin/ponentes/editar", [
                "titulo" => $titulo,
                "alertas" => $alertas,
                "ponente" => $ponente,
                "redes" => json_decode($ponente->redes) //Al string de redes que viene desde la bd lo convierto a objeto
            ]);
        }

        public static function eliminar() {
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                if(!is_admin()) {
                    header("Location: /login");
                }
                
                $id = $_POST["id"];
                $ponente = Ponente::find($id);
                
                if(isset($ponente)) {
                    $resultado = $ponente->eliminar();
                    if($resultado) {
                        header("Location: /admin/ponentes");    //redirecciono para que se refresque la pantalla con el listado actualizado
                    }
                } else {
                    header("Location: /admin/ponentes");
                }
            }
        }

    }

?>