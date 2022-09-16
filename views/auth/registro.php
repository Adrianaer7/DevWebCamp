<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Regístrate en DevWebCamp</p>

    <?php 
        require_once __DIR__ . "/../templates/alertas.php"
    ?>

    <form class="formulario" method="POST" action="/registro">
        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre</label>
            <input 
                type="text" 
                name="nombre" 
                id="nombre"
                class="formulario__input"
                placeholder="Tu nombre"
            >

            <label for="apellido" class="formulario__label">Apellido</label>
            <input 
                type="text" 
                name="apellido" 
                id="apellido"
                class="formulario__input"
                placeholder="Tu apellido"
            >

            <label for="email" class="formulario__label">Email</label>
            <input 
                type="email" 
                name="email" 
                id="email"
                class="formulario__input"
                placeholder="Tu email"
            >

            <label for="password" class="formulario__label">Contraseña</label>
            <input 
                type="password" 
                name="password" 
                id="password"
                class="formulario__input"
                placeholder="Tu contraseña"
            >

            <label for="password2" class="formulario__label">Repetir contraseña</label>
            <input 
                type="password" 
                name="password2" 
                id="password2"
                class="formulario__input"
                placeholder="Repite la contraseña"
            >

            <input 
                type="submit"
                class="formulario__submit"
                value="Crear cuenta"
            >
        </div>

        <div class="acciones">
            <a href="/login" class="acciones__enlace">¿Ya tienes cuenta?</a>
            <a href="/olvide" class="acciones__enlace">¿Olvidaste tu password?</a>

        </div>
    </form>
</main>