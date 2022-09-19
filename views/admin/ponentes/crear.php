<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a href="/admin/ponentes/crear" class="dashboard__boton">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<div class="dashboard__formulario">
    <?php include_once __DIR__ . "../../../templates/alertas.php"?>

    <form class="formulario" method="POST" action="/admin/ponentes/crear" enctype="multipart/form-data">
        <?php include_once __DIR__ . "/formulario.php" ?>

    <input 
        type="submit"
        class="formulario__submit formulario__submit--registrar"
        value="Registrar Ponente"
    >
    </form>
</div>