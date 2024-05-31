<!DOCTYPE html>
<html>
<head>
    <title>Museo Mojang</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/base.css"/>
    <link rel="stylesheet" type="text/css" href="css/experiencias.css"/>
    <meta name="viewport" content="width=device-width">
</head>
<body>
    <header>
        <!-- Cabecera del sitio -->
        <section class="cabecera">
            <a href="index.php"><img src="imagenes/Logo.png" id="logo" alt="logo" class="logo"> </a>
        </section>

        <!-- Menú de navegación -->
        <nav class="Navegador">
            <ul class="Navegador-Links">
                <li><a class="enlace-nav" href="index.php">Página principal</a></li>
                <li><a class="enlace-nav" href="coleccion.php">Colección</a></li>
                <li><a class="enlace-nav" href="visita.html">Visita</a></li>
                <li><a class="enlace-nav" href="exposiciones.html">Exposiciones</a></li>
                <li><a class="enlace-nav" href="informacion.html">Información</a></li>
                <li><a class="enlace-nav actual" href="experiencias.php">Experiencias</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>EXPERIENCIAS DE NUESTROS USUARIOS</h1>

        <section class="contenedor-principal">
            <section class="contenedor-experiencias">
                <?php
                // Conectar a la base de datos y obtener los comentarios
                try {
                    $conn = new PDO("mysql:host=localhost;dbname=dbalbertoov_pw2324", "pwalbertoov", "23albertoov24");
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $conn->query("SELECT usuarios.nombre, usuarios.genero, comentarios.comentario, comentarios.valoracion, comentarios.fecha FROM comentarios JOIN usuarios ON comentarios.email = usuarios.email ORDER BY comentarios.fecha DESC");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $imagen = "";
                        if ($row['genero'] == 'masculino') {
                            $imagen = "imagenes/hombre.png";
                        } elseif ($row['genero'] == 'femenino') {
                            $imagen = "imagenes/mujer.png";
                        } else {
                            $imagen = "imagenes/otro.PNG";
                        }
                        echo "<article class='opinion'>
                            <section class='valoracion'>
                                <article class='usuario'>
                                    <img src='$imagen' alt='{$row['genero']}'>
                                    <p>{$row['nombre']}</p>
                                </article>
                                <article class='fecha'>
                                    <p>{$row['fecha']}</p>
                                </article>
                                <article class='puntuacion'>
                                    <p>{$row['valoracion']}/5</p>
                                </article>
                            </section>
                            <section class='comentario'>
                                <article class='comentario'>
                                    <p>{$row['comentario']}</p>
                                </article>
                            </section>
                        </article>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </section>

            <!-- Formulario para dejar una experiencia -->
            <section class="contenedor-formulario">
                <h2>¿Quieres dejar una reseña?</h2>
                <h3>Recuerda iniciar sesión para poder realizar un comentario</h3>
                
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p style='color:red;'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>

                <form action="procesar_comentario.php" method="POST">
                    <label for="valoracion">Valoración de la experiencia:</label>
                    <select id="valoracion" name="valoracion">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select><br><br>

                    <label for="comentarios">Comentarios:</label><br>
                    <textarea id="comentarios" name="comentarios" rows="4" cols="50"></textarea><br><br>
                    
                    <input type="submit" id="enviar-valoracion" value="Realizar Valoracion">
                </form>
            </section>
        </section>
    </main>
    
    <footer>
        <a class="comosehizo" href="pie_pagina/como_se_hizo.pdf">¿Cómo se hizo?</a>
        <a class="contact" href="pie_pagina/contacto.html">Contacto</a>
        <p>Derechos de autor © 2024 Museo Mojang.</p>
    </footer>
</body>
</html>
