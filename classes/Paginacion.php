<?php 
    namespace Classes;
  
    class Paginacion {
        public $pagina_actual;
        public $registros_por_pagina;
        public $total_registros;

        public function __construct($pagina_actual = 1, $registros_por_pagina = 10, $total_registros = 0)    //si no le recibo como parametro un numero, se lo asigno por default
        {
            $this->pagina_actual = (int) $pagina_actual;    //si el numero llega como string, lo cambio a integer gracias a (int)
            $this->registros_por_pagina = (int) $registros_por_pagina;
            $this->total_registros = (int) $total_registros;
        }

        //si los registros_por_pagina son 10, los valores que devuelve van a ser 0,10,20,30, etc.
        public function offset() : int {
            return $this->registros_por_pagina * ($this->pagina_actual - 1);
        }

        //numero de paginaciones
        public function total_paginas() : int {
            return ceil($this->total_registros / $this->registros_por_pagina);
        }

        //numeros que se muestran entre el boton anterior y siguiente
        public function numeros_paginas() {
            $html = "";
            for($i = 1; $i <= $this->total_paginas(); $i++) {
                if($i === $this->pagina_actual) {
                    $html .= 
                        "<span 
                            class=\"paginacion__enlace paginacion__enlace--actual\"
                        >
                            {$i}
                        </span>";
                } else {
                    $html .= 
                        "<a 
                            class=\"paginacion__enlace paginacion__enlace--numero\" 
                            href=\"?page={$i}\"
                        >
                            $i
                        </a>";
                }
            }
            return $html;
        }

        public function pagina_anterior() : int {
            $anterior = $this->pagina_actual - 1;
            return ($anterior > 0) ? $anterior : false; //no puedo ir a una pagina anterior a la 0
        }

        public function pagina_siguiente() : int {
            $siguiente = $this->pagina_actual + 1;
            return ($siguiente > $this->total_paginas()) ? false : $siguiente;  //no puedo ir a una pagina mayor a la cantidad de paginas que calcula la fn
        }

        public function enlace_anterior() : string {
            $html = "";
            if($this->pagina_anterior()) {
                $html.= 
                    "<a 
                       class=\"paginacion__enlace paginacion__enlace--texto\" 
                       href=\"?page={$this->pagina_anterior()}\">&laquo; Anterior 
                    </a>"; //Redirecciono al usuario a la siguiente pagina. "escapo" el html utilizando barra invertida antes de cada comilla
            }
            
            return $html;
        }
        public function enlace_siguiente() : string {
            $html = "";
            if($this->pagina_siguiente()) {
                $html.= 
                    "<a 
                       class=\"paginacion__enlace paginacion__enlace--texto\" 
                       href=\"?page={$this->pagina_siguiente()}\">Siguiente &raquo;
                    </a>"; //Redirecciono al usuario a la siguiente pagina. "escapo" el html utilizando barra invertida antes de cada comilla
            }
            return $html;
        }

        public function paginacion() : string {
            $html = "";
            if($this->total_registros > 1) {
                $html .= '<div class="paginacion">';
                $html .= $this->enlace_anterior();
                $html .= $this->numeros_paginas();
                $html .= $this->enlace_siguiente();
                $html .= '</div>';
            }
            return $html;

        }
    }
?>