<?php 
    namespace Controllers;

use Model\EventoHorario;

    class APIEventos {
        public static function index() {
            //Verifico si en el formulario seleccione el tipo de evento y el dia
            $dia_id = $_GET["dia_id"] ?? "";
            $categoria_id = $_GET["categoria_id"] ?? "";

            $dia_id = filter_var($dia_id, FILTER_VALIDATE_INT);
            $dia_id = filter_var($categoria_id, FILTER_VALIDATE_INT);

            if(!$dia_id || !$categoria_id) {
              echo json_encode([]);     //si no le paso como parametro en la url el dia y la categoria, devuelve un Json []
              return;
            }

            //Consultar BD si existe en la url dia y categoria. La consulta la hago desde el hora.js
            $eventos = EventoHorario::whereArray(["dia_id" => $dia_id, "categoria_id" => $categoria_id] ?? null); //en caso de que la consulta devuelva null, guardo en $eventos: [] 
            //convierto a json los eventos para enviarlos al "body" y asi consumir le dato en hora.js
            echo json_encode($eventos);
        }
    }
?>