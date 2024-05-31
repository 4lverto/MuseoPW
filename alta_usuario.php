<!DOCTYPE html>
<html>

    <head>
        <title>Alta de Usuario</title>
        <meta charset="utf-8"/>
        
        <link rel = "stylesheet" type = "text/css" href="css/alta.css"/>
        <link rel = "stylesheet" type = "text/css" href="css/base.css"/>
        
    </head>

    <body>

        <header>
            <!-- Uso una clase Cabecera que contendrá el Logo del museo y el Formulario-->
            
            <section class="cabecera">
                <a href="index.php"><img src="imagenes/Logo.png" id="logo" alt="logo" class="logo"> </a>


                <section class="bienvenida">
                    <h3> Bienvenid@ al Museo Mojang</h3>
                    <p> Para darte de alta como usuario, completa el formulario</p>
                </section> 

                <!-- Usamos una clase InicioSesion que contendrá el formulario de inicio de sesión-->
                <section class="InicioSesion">
                    <form>
                        <label for="Correo">Correo electrónico: </label><br>
                        <input type="text" id="Correo" name="Correo" placeholder="ejemplo@gmail.com"><br>

                        <label for="Contraseña">Contraseña: </label><br>
                        <input type="password" id="Contraseña" name="Contraseña"><br>

                        <input type="submit" id="iniciar" value="Iniciar Sesión"><br>
                    </form>

                    <a href="alta_usuario.html" id="registro">Registrarse</a>
                </section>
            </section>

            <!-- Sección que corresponderá con el menú de navegación-->
            <section class="nav-container">
                <nav class="Navegador">
                    <ul class="Navegador-Links">
                        <li><a class="enlace-nav" href="index.php">Página principal</a></li>
                        <li><a class="enlace-nav" href="coleccion.php">Colección</a></li>
                        <li><a class="enlace-nav" href="visita.html">Visita</a></li>
                        <li><a class="enlace-nav" href="exposiciones.html">Exposiciones</a></li>
                        <li><a class="enlace-nav" href="informacion.html">Información</a></li>
                        <li><a class="enlace-nav" href="experiencias.php">Experiencias</a></li>
                    </ul>
                </nav>
            </section>

        </header>

        <main>

            <section class="volver">
                <a href="index.php">  <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="currentColor" class="boton-volver" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                    <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                    </svg>
                </a>

                <h2> Volver al Museo </h2>
            </section>

            <section class="formularios">
                    
                <form action="registrar_usuario.php" method="POST">
                        
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Alberto" required><br><br>
                    
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="albertoov@correo.ugr.es"required> <br><br>
                    
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password"required><br><br>
                    
                    <label for="edad">Edad:</label>
                    <input type="number" id="edad" name="edad" min="0" max="150"required><br><br>
                    
                    <label for="genero">Género:</label> <!-- Lista desplegable-->
                    <select id="genero" name="genero" required>
                        <option value="">Seleccione</option>
                        <option value="masculino">Masculino</option>
                        <option value="femenino">Femenino</option>
                        <option value="otro">Otro</option>
                    </select><br><br>
                    
                    <input type="submit" id="Registrarse" value="Registrarse">

                </form>
            </section>


        </main>

        <footer>
            <p> <a href="pie_pagina/como_se_hizo.pdf">¿Cómo se hizo?</a></p>
            <p> <a href="pie_pagina/contacto.html">Contacto</a></p>
            <p>Derechos de autor © 2024 Museo Mojang.</p>
        </footer>
    </body>


</html>