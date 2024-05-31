<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Museo Mojang</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/base.css"/> 
    <link rel="stylesheet" type="text/css" href="css/indice.css"/>
    <meta name="viewport" content="width=device-width", initial-scale="1.0">
</head>
<body>
    <header>
        <section class="cabecera">
            <a href="index.php"><img src="imagenes/Logo.png" id="logo" alt="logo" class="logo"> </a>
            <section class="InicioSesion">
                <?php if (isset($_SESSION['nombre'])): ?>
                    <p>Bienvenido, <?php echo $_SESSION['nombre']; ?> - <?php echo $_SESSION['tipo']; ?></p>
                    <a href="cerrar_sesion.php" id="cerrar">Cerrar Sesión</a>
                <?php else: ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <p style="color:red;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                    <?php endif; ?>
                    <form action="iniciar_sesion.php" method="POST">
                        <label for="Usuario">Nombre de Usuario: </label><br>
                        <input type="text" id="Usuario" name="nombre" placeholder="soyUnUsuario"><br>
                        <label for="Contraseña">Contraseña: </label><br>
                        <input type="password" id="Contraseña" name="password"><br>
                        <input type="submit" id="iniciar" value="Iniciar Sesión"><br>
                    </form>
                    <a href="alta_usuario.php" id="registro">Registrarse</a>
                <?php endif; ?>
            </section>
        </section>
        <section class="contenedor-links">
            <nav>
                <ul class="Navegador-Links">
                    <li><a class="enlace-nav actual" href="index.php">Página principal</a></li>
                    <li><a class="enlace-nav" href="coleccion.html">Colección</a></li>
                    <li><a class="enlace-nav" href="visita.html">Visita</a></li>
                    <li><a class="enlace-nav" href="exposiciones.html">Exposiciones</a></li>
                    <li><a class="enlace-nav" href="informacion.html">Información</a></li>
                    <li><a class="enlace-nav" href="experiencias.php">Experiencias</a></li>
                </ul>
            </nav>
        </section>
    </header>
    <main>
        <section class="TituloMuseo">
            <h1 id="MuseoMojang"> MUSEO MOJANG</h1>
        </section>
        <section class="Presentacion">
            <p>Disfruta cada momento, cada pincelada y cada escultura, porque en este espacio, el arte cobra vida.</p>
        </section>
    </main>
    <footer>
        <a class="comosehizo" href="pie_pagina/como_se_hizo.pdf">¿Cómo se hizo?</a>
        <a class="contact" href="pie_pagina/contacto.html">Contacto</a>
        <p>Derechos de autor © 2024 Museo Mojang.</p>
    </footer>
</body>
</html>
