<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
    <a href="/perfil" class="enlace">Volver al perfil</a>
    <form action="/cambiar-password" method="post" class="formulario">
        <div class="campo">
            <label for="password-actual">Password Actual</label>
            <input 
                type="password"
                name="password-actual"
                id="password-actual"
                placeholder="Tu Password Actual"
            />
        </div>

        <div class="campo">
            <label for="password-nuevo">Password Nuevo</label>
            <input 
                type="password"
                name="password-nuevo"
                id="password-nuevo"
                placeholder="Tu Nuevo Password"
            />
        </div>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php'; ?>