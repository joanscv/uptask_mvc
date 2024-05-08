<div class="contenedor restablecer">
  <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>
  <div class="contenedor-sm">
    <p class="descripcion-pagina">Coloca tu nuevo password</p>
    <?php include_once __DIR__ . "/../templates/alertas.php" ?>
    <?php if($mostrarCampos): ?>
      <form method="post" class="formulario">
        <div class="campo">
          <label for="password">Password</label>
          <input type="password" 
                id="password" 
                name="password" 
                placeholder="Tu Password">
        </div>
        <div class="campo">
          <label for="password2">Reescribe Password</label>
          <input type="password" 
                id="password2" 
                name="password2" 
                placeholder="Repite Password">
        </div>
        <input type="submit" class="boton" value="Guardar Password">
      </form>
    <?php endif; ?>

    <div class="acciones">
      <a href="/">Iniciar Sesión</a>
    </div>
  </div>
</div>