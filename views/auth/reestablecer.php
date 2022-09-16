<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Introduce tu nueva contraseña</p>

    <?php 
        require_once __DIR__ . "/../templates/alertas.php"
    ?>

    <?php if($token_valido) { ?>
        <form class="formulario" method="POST">
            <div class="formulario__campo">
                <label for="password" class="formulario__label">Nuevo password</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password"
                    class="formulario__input"
                    placeholder="Tu contraseña"
                >
    
                <input 
                    type="submit"
                    class="formulario__submit"
                    value="Guardar contraseña"
                >
            </div>
    
            <div class="acciones">
                <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? Iniciar sesión</a>
                <a href="/registro" class="acciones__enlace">¿Aun no tienes cuenta? Obtener una</a>
    
            </div>
        </form>
    <?php } ?>
</main>