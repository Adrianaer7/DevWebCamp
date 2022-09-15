<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Inicia sesion en DevWebCamp</p>

    <form class="formulario">
        <div class="formulario__campo">
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

            <input 
                type="submit"
                class="formulario__submit"
                value="Iniciar sesión"
            >
        </div>

        <div class="acciones">
            <a href="/registro" class="acciones__enlace">¿Aun no tienes cuenta? Obtener una</a>
            <a href="/olvide" class="acciones__enlace">Olvidaste tu password?</a>

        </div>
    </form>
</main>