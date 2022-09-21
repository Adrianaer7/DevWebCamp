<?php 

    namespace Controllers;

    use Model\Ponente;
    use MVC\Router;
    use Intervention\Image\ImageManagerStatic as Image;

    class PonentesController {
        
        public static function index(Router $router) {
            $titulo = "Ponentes / Conferencistas";

            $ponentes = Ponente::all();
            
            $router->render("admin/ponentes/index", [
                "titulo" => $titulo,
                "ponentes" => $ponentes
            ]);
        }

        public static function crear(Router $router) {
            $titulo = "Registrar Ponente";
            $alertas = [];
            $ponente = new Ponente;

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
                $ponente->sincronizar($_POST);
                
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
            $ponente = "";

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

            

            $router->render("admin/ponentes/editar", [
                "titulo" => $titulo,
                "alertas" => $alertas,
                "ponente" => $ponente,
                "redes" => json_decode($ponente->redes) //Al string de redes que viene desde la bd lo convierto a objeto
            ]);
        }
    }

?>