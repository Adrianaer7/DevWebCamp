<?php

namespace Controllers;

use Model\Evento;
use MVC\Router;

class PaginasController {
    public static function index(Router $router) {
        $titulo = "Inicio";

        echo "inicio";

        $router->render("paginas/index", [
            "titulo" => $titulo
        ]);
    }

    public static function evento(Router $router) {
        $titulo = "Sobre DevWebCamp";


        $router->render("paginas/devwebcamp", [
            "titulo" => $titulo
        ]);
    }

    public static function paquetes(Router $router) {
        $titulo = "Paquetes DevWebCamp";
        
        echo "paquetes";

        $router->render("paginas/paquetes", [
            "titulo" => $titulo
        ]);
    }

    public static function conferencias(Router $router) {
        $titulo = "Conferencias & Workshops";

        $eventos = Evento::ordenar("hora_id", "ASC");
        debugear($eventos);
        
        $router->render("paginas/conferencias", [
            "titulo" => $titulo
        ]);
    }
}