<?php

function debugear($variable) : string {
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function pagina_actual($path) : bool {
    return str_contains($_SERVER["PATH_INFO"] ?? "/", $path);
}

function is_auth() : bool {
    if(!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION["nombre"]) && !empty($_SESSION);
}

function is_admin() : bool {
    if(!isset($_SESSION)) {
        session_start();
    }
    return isset($_SESSION["admin"]) && !empty($_SESSION["admin"]);
}

//utilizo esta funcion en los elementos html que quiera animar
function aos_animacion() : void {
    //Creo un array con los nombres de animaciones que mas me gusten de aos
    $efectos = ["fade-up", "fade-down", "fade-right", "fade-left", "flip-left", "flip-right", "zoom-in", "zoom-in-up", "zoom-in-down", "zoom-out"];

    //Elije aleatoriamente un string del array
    $efecto = array_rand($efectos, 1);  //devuelve la posicion
    echo ' data-aos="' . $efectos[$efecto] . '" '; //devuelvo string con nombre del atributo html para poder aplicar la animacion y el string de la posicion
}