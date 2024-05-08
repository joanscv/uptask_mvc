<?php include_once __DIR__ . '/header-dashboard.php';?>
    
    <?php if(count($proyectos)===0):?>
        <p class="no-proyectos">No Hay Proyectos Aún.</p>
        <a class="enlace-crear" href="/crear-proyecto">Comienza creando uno</a>
    <?php else: ?>
        <ul class="listado-proyectos">
            <?php foreach($proyectos as $proyecto): ?>
                <li>
                    <a class="proyecto" href="/proyecto?id=<?php echo $proyecto->url; ?>">
                        <?php echo $proyecto->proyecto; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    
    <?php endif; ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>