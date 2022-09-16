<?php 
    foreach($alertas as $key => $alerta) {  //$alertas es un array que tiene un array de columnas($key) y cada columna($alerta) tiene un mensaje
        foreach($alerta as $mensaje) {
?>
    <div class="alerta alerta__<?php echo $key;?>">
            <?php echo $mensaje; ?>
    </div>

<?php   }
    }
?>