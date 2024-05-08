<div class="barra-mobile">
    <h1>UpTask</h1>
    <div class="menu">
        <img src="build/img/menu.svg" id="mobile-menu" alt="Imagen menú">
    </div>
</div>

<div class="barra">
    <p>Hola: <span><?php echo $_SESSION['nombre'] . " " . 
        $_SESSION['apellido']; ?></span></p>
    <a href="/logout" class="cerrar-sesion">Cerrar Sesión</a>
</div>