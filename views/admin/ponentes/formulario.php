<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Personal</legend>

    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre</label>
        <input 
            type="text" 
            class="formulario__input"
            name="nombre" 
            id="nombre"
            placeholder="Nombre del ponente"
            value="<?php echo s($ponente->nombre) ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="apellido" class="formulario__label">Apellido</label>
        <input 
            type="text" 
            class="formulario__input"
            name="apellido" 
            id="apellido"
            placeholder="Apellido del ponente"
            value="<?php echo s($ponente->apellido) ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="pais" class="formulario__label">Pais</label>
        <input 
            type="text" 
            class="formulario__input"
            name="pais" 
            id="pais"
            placeholder="Pais del ponente"
            value="<?php echo s($ponente->pais) ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="ciudad" class="formulario__label">Ciudad</label>
        <input 
            type="text" 
            class="formulario__input"
            name="ciudad" 
            id="ciudad"
            placeholder="Ciudad del ponente"
            value="<?php echo s($ponente->ciudad) ?? "" ?>"
        >
    </div>
    <div class="formulario__campo">
        <label for="imagen" class="formulario__label">Imagen</label>
        <input 
            type="file" 
            class="formulario__input formulario__input--file"
            name="imagen" 
            id="imagen"
        >
    </div>
    <?php if($ponente->imagen_actual && $_SERVER["SCRIPT_NAME"] !== "/admin/ponentes/crear.php") { ?>
        <p class="formulario__texto">Imagen actual</p>
        <div class="formulario__imagen">
            <picture>
                <source 
                    srcset="<?php echo '/img/speakers/' . $ponente->imagen ;?>.webp"
                    type="image/webp"
                >
                <source 
                    srcset="<?php echo '/img/speakers/' . $ponente->imagen ;?>.png"
                    type="image/png"
                >
                <img 
                    src="<?php echo '/img/speakers/' . $ponente->imagen ;?>.png" 
                    alt="Imagen Ponente"
                >
            </picture>
        </div>
    <?php }; ?>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Extra</legend>

    <div class="formulario__campo">
        <label for="tags_input" class="formulario__label">Areas de experiencia</label>
        <input 
            type="text" 
            class="formulario__input"
            id="tags_input"
            placeholder="Ej. Node.js, React.js, etc..."
        >
        <div class="formulario__listado" id="tags"> <!--dentro del div, incluyo los li creados en el tags.js-->
            
        </div>
        <input 
            type="hidden"
            name="tags"
            value="<?php echo s($ponente->tags) ?? "" ?>"
        >
    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Redes sociales</legend>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-facebook"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[facebook]" 
                placeholder="Facebook"
                value="<?php echo s($redes->facebook) ?? "" ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-twitter"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[twitter]" 
                placeholder="Twitter"
                value="<?php echo s($redes->twitter) ?? "" ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-youtube"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[youtube]" 
                placeholder="YouTube"
                value="<?php echo s($redes->youtube) ?? "" ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-instagram"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[instagram]" 
                placeholder="Instagram"
                value="<?php echo s($redes->instagram) ?? "" ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-tiktok"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[tiktok]" 
                placeholder="TikTok"
                value="<?php echo s($redes->tiktok) ?? "" ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <div class="formulario__contenedor-icono">
            <div class="formulario__icono">
                <i class="fa-brands fa-github"></i>
            </div>
            <input 
                type="text" 
                class="formulario__input--sociales"
                name="redes[github]" 
                placeholder="GitHub"
                value="<?php echo s($redes->github) ?? "" ?>"
            >
        </div>
    </div>

</fieldset>