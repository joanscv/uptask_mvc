<div class="contenedor confirmar">
  <?php include_once __DIR__ . "/../templates/nombre-sitio.php" ?>
  <div class="contenedor-sm">
    <?php include_once __DIR__ . "/../templates/alertas.php" ?>
    <?php if(empty($alertas['error'])): ?>
      <p class="descripcion-pagina">Felicidades, has confirmado tu cuenta.</p>
      <box-icon class="icon-confirmar" name='party'></box-icon>
    <?php endif; ?>
    <div class="acciones">
      <a href="/">Iniciar Sesión</a>
    </div>
  </div>
</div>