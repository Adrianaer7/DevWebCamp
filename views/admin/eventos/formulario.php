<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion Evento</legend>

    <div class="formulario__campo">
        <label for="nombre" class="formulario__label">Nombre</label>
        <input 
            type="text" 
            class="formulario__input"
            name="nombre" 
            id="nombre"
            placeholder="Nombre del evento"
            value="<?php echo s($evento->nombre) ?? "" ?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="descripcion" class="formulario__label">Descripcion</label>
        <textarea 
            class="formulario__input"
            name="descripcion" 
            id="descripcion"
            rows="8"
            placeholder="Descripcion del evento"
        ><?php echo s($evento->descripcion) ?? "" ?></textarea>
    </div>

    <div class="formulario__campo">
        <label for="categoria" class="formulario__label">Categoria o tipo de evento</label>
        <select 
            class="formulario__select"
            id="categoria"
            name="categoria_id"
        >
            <option value="">--- Seleccione ---</option>
            <?php foreach($categorias as $categoria) { ?>
                <option 
                    <?php echo ($evento->categoria_id === $categoria->id) ? "selected" : ""; ?>
                    value="<?php echo s($categoria->id) ?>"
                >
                    <?php echo $categoria->nombre; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="formulario__campo">
        <label for="dia" class="formulario__label">Selecciona el dia</label>
        <div class="formulario__radio">
            <?php foreach($dias as $dia) { ?>
                <div>
                    <label for="<?php echo strtolower($dia->nombre)?>"><?php echo $dia->nombre; ?></label>
                    <input 
                        type="radio" 
                        name="dia" 
                        id="<?php echo strtolower($dia->nombre)?>"
                        value="<?php echo s($dia->id)?>"
                        <?php echo $evento->dia_id === $dia->id ? "checked" : "" ;?>
                    >
                </div>
            <?php } ?>
            <input 
                type="hidden"
                name="dia_id"
                value="<?php echo s($evento->dia_id) ?>"
            >
        </div>
    </div>

    <div class="formulario__campo">
        <label for="hora" class="formulario__label">Selecciona la hora</label>
        <ul id="horas" class="horas">
            <!--Puedo agregar atributos con nombre personalizado, siempre y cuando empiecen con "data"-->
            <?php foreach($horas as $hora) { ?>
                <li class="horas__hora horas__hora--deshabilitada" data-hora-id="<?php echo $hora->id?>"><?php echo $hora->hora; ?></li>   
            <?php } ?>
        </ul>
        <input 
            type="hidden"
            name="hora_id"
            value="<?php echo s($evento->hora_id)?>"
        >
    </div>
</fieldset>

<fieldset class="formulario__fieldset">
    <legend class="formulario__legend">Informacion extra</legend>

    <div class="formulario__campo">
        <label for="ponentes" class="formulario__label">Ponente</label>
        <input 
            type="text"
            class="formulario__input"
            id="ponentes"
            placeholder="Busca el nombre del ponente"
        />
        <ul id="listado-ponentes" class="listado-ponentes">

        </ul>
        <input 
            type="hidden" 
            name="ponente_id"
            value="<?php echo s($evento->ponente_id) ;?>"
        >
    </div>

    <div class="formulario__campo">
        <label for="disponibles" class="formulario__label">Lugares disponibles</label>
        <input 
            type="number"
            class="formulario__input"
            id="disponibles"
            name="disponibles"
            min="1"
            placeholder="Ej. 20"
            value="<?php echo s($evento->disponibles) ?? "" ?>"
        />
    </div>
</fieldset>