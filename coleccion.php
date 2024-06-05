<?php
    session_start();
    include 'conexion_bd.inc';

    // Verificamos la conexión a la base de datos
    try {
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Conexión fallida: " . $e->getMessage());
    }

    // ////////////////////////////// //
    // Funciones para gestionar obras //
    // ////////////////////////////// //

    function obtenerObras($categoria = null, $subcategoria = null) {
        global $conexion;
        $consultaSQL = "SELECT * FROM obras";

        // Si se aplica algún filtro (categoría o categoria+subcategoria), se modifica la consulta aplicando dicho filtro.
        if ($categoria && $subcategoria) {
            $consultaSQL .= " WHERE categoria = :categoria AND subcategoria = :subcategoria";
            $sentenciaSQL = $conexion->prepare($consultaSQL);
            $sentenciaSQL->bindParam(':categoria', $categoria);
            $sentenciaSQL->bindParam(':subcategoria', $subcategoria);
        } elseif ($categoria) {
            $consultaSQL .= " WHERE categoria = :categoria";
            $sentenciaSQL = $conexion->prepare($consultaSQL);
            $sentenciaSQL->bindParam(':categoria', $categoria);
        } else {
            $sentenciaSQL = $conexion->prepare($consultaSQL);
        }
        $sentenciaSQL->execute();

        // Se devuelve el resultado de ejecutar la consulta como un array (fetchAll)
        return $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
    }

    // Función que devuelve todas las (diferentes) categorías de las obras
    function obtenerCategorias() {
        global $conexion;
        $consultaSQL = "SELECT DISTINCT categoria FROM obras";
        $sentenciaSQL = $conexion->query($consultaSQL);
        return $sentenciaSQL->fetchAll(PDO::FETCH_COLUMN);
    }

    // Función que devuelve todas las (diferentes) subcategorías que pertenecen a una categoría determinada
    function obtenerSubcategorias($categoria) {
        global $conexion;
        $consultaSQL = "SELECT DISTINCT subcategoria FROM obras WHERE categoria = :categoria";
        $sentenciaSQL = $conexion->prepare($consultaSQL);
        $sentenciaSQL->bindParam(':categoria', $categoria);
        $sentenciaSQL->execute();
        return $sentenciaSQL->fetchAll(PDO::FETCH_COLUMN);
    }

    function obtenerImagenes() {
        $dir = 'obras_fotos/';
        $imagenes = array_diff(scandir($dir), array('..', '.'));
        return $imagenes;
    }

    function obtenerObra($id) {
        global $conexion;
        $consultaSQL = "SELECT * FROM obras WHERE id = :id";
        $sentenciaSQL = $conexion->prepare($consultaSQL);
        $sentenciaSQL->bindParam(':id', $id);
        $sentenciaSQL->execute();
        return $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
    }

    function agregarObra($categoria, $subcategoria, $titulo, $url, $autor, $fecha) {
        global $conexion;
        $consultaSQL = "INSERT INTO obras (categoria, subcategoria, titulo, url, autor, fecha) VALUES (:categoria, :subcategoria, :titulo, :url, :autor, :fecha)";
        $sentenciaSQL = $conexion->prepare($consultaSQL);
        $sentenciaSQL->bindParam(':categoria', $categoria);
        $sentenciaSQL->bindParam(':subcategoria', $subcategoria);
        $sentenciaSQL->bindParam(':titulo', $titulo);
        $sentenciaSQL->bindParam(':url', $url);
        $sentenciaSQL->bindParam(':autor', $autor);
        $sentenciaSQL->bindParam(':fecha', $fecha);
        $sentenciaSQL->execute();
    }

    function modificarObra($id, $categoria, $subcategoria, $titulo, $url, $autor, $fecha) {
        global $conexion;
        $consultaSQL = "UPDATE obras SET categoria = :categoria, subcategoria = :subcategoria, titulo = :titulo, url = :url, autor = :autor, fecha = :fecha WHERE id = :id";
        $sentenciaSQL = $conexion->prepare($consultaSQL);
        $sentenciaSQL->bindParam(':id', $id);
        $sentenciaSQL->bindParam(':categoria', $categoria);
        $sentenciaSQL->bindParam(':subcategoria', $subcategoria);
        $sentenciaSQL->bindParam(':titulo', $titulo);
        $sentenciaSQL->bindParam(':url', $url);
        $sentenciaSQL->bindParam(':autor', $autor);
        $sentenciaSQL->bindParam(':fecha', $fecha);
        $sentenciaSQL->execute();
    }

    function eliminarObra($id) {
        global $conexion;
        $consultaSQL = "DELETE FROM obras WHERE id = :id";
        $sentenciaSQL = $conexion->prepare($consultaSQL);
        $sentenciaSQL->bindParam(':id', $id);
        $sentenciaSQL->execute();
    }

                                                        // ////////////////////// //
                                                        // Gestión de solicitudes //
                                                        // ////////////////////// //

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Si el campo 'subcategoría' está presente en los datos del formulario enviados usando 'POST',
        // se le asigna dicho valor a la variable '$subcategoria'. Esto servirá para poder
        // manejar el que el usuario tan solo filtre por categoría, por ejemplo, sin especificar
        // uns subcategoría.
        if (isset($_POST['subcategoria'])) {
            $subcategoria = $_POST['subcategoria'];
        } else {
            $subcategoria = null;
        }
        
        // Si se envía el formulario correspondiente a agregar obra:
        if (isset($_POST['agregar'])) {
            agregarObra($_POST['categoria'], $subcategoria, $_POST['titulo'], $_POST['url'], $_POST['autor'], $_POST['fecha']);
        
            // Si se envía el formulario correspondiente a eliminar obra:     
        } elseif (isset($_POST['eliminar'])) {
            eliminarObra($_POST['id']);
        
            // Si se envía el formulario correspondiente a modificar obra:
        } elseif (isset($_POST['modificar'])) {
            modificarObra($_POST['id'], $_POST['categoria'], $subcategoria, $_POST['titulo'], $_POST['url'], $_POST['autor'], $_POST['fecha']);
        }
    }

    // Si en la solitud GET se encuentra "modificar" significará que se ha "pulsado" el botón de modificar una obra.
    // En este caso, es establecerá la obra seleccionada llamarando a "obtenerObra" proporcionando el ID (que estaba contenido en el botón oculto)
    if (isset($_GET['modificar'])) {
        $obraSeleccionada = obtenerObra($_GET['modificar']);
    } else {
        $obraSeleccionada = null;
    }

    // Si en la solicitud GET se encuentra "categoría" significará que se ha pulsado sobre una de las categorías (Construcciones, Paisajes, Animales)
    if (isset($_GET['categoria'])) {
        $categoriaSeleccionada = $_GET['categoria'];
    } else {
        $categoriaSeleccionada = null;
    }

    // Si en la solicitud GET se encuentra "subcategoría" significará que se ha pulsado sobre una de las subcategorías
    if (isset($_GET['subcategoria'])) {
        $subcategoriaSeleccionada = $_GET['subcategoria'];
    } else {
        $subcategoriaSeleccionada = null;
    }

    // Se definen las siguientes variables de cara a mostrar correctamente las obras en función de los diferentes filtros posibles que se pueden aplicar
    $obras = obtenerObras($categoriaSeleccionada, $subcategoriaSeleccionada);
    $categorias = obtenerCategorias();
    $imagenes = obtenerImagenes();
?>

            <!-- SECCIÓN HTML -->

<!DOCTYPE html>
<html>
<head>
    <title>Museo Mojang</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" type="text/css" href="css/base.css"/>
    <link rel="stylesheet" type="text/css" href="css/coleccion.css"/>
    <meta name="viewport" content="width=device-width">
</head>
<body>
    <header>
        <section class="cabecera">
            <a href="index.php"><img src="imagenes/Logo.png" id="logo" alt="logo" class="logo"></a>
        </section>
        <nav class="Navegador">
            <ul class="Navegador-Links">
                <li><a class="enlace-nav" href="index.php">Página principal</a></li>
                <li><a class="enlace-nav actual" href="coleccion.php">Colección</a></li>
                <li><a class="enlace-nav" href="visita.html">Visita</a></li>
                <li><a class="enlace-nav" href="exposiciones.html">Exposiciones</a></li>
                <li><a class="enlace-nav" href="informacion.html">Información</a></li>
                <li><a class="enlace-nav" href="experiencias.php">Experiencias</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="contenedor-filtros">
            <h2 class="filtrar-obras">Filtrar Obras</h2>
            <section class="cada-filtro">
                <article class="filtro">
                    <button class="drop-boton">Categoría</button>
                    <section class="drop-contenido">
                        <!-- Se muestran todas las categorías a la espera de que el usuario seleccione una --> 
                        <?php 
                            foreach ($categorias as $categoria): 
                        ?>
                            <!-- Se reenvía al usuario a la página con la url= coleccion.php?categoria=___ -->
                            <a href="coleccion.php?categoria=<?php echo $categoria; ?>"><?php echo $categoria; ?></a>
                        <?php endforeach; ?>
                    </section>
                </article>
                <article class="filtro">
                    <button class="drop-boton">Subcategoría</button>
                    <section class="drop-contenido">
                        <!-- Si el usuario ha seleccionado una categoría, se muestran las sub-categorías correspondientes a esta -->
                        <?php if ($categoriaSeleccionada): ?>
                            <?php $subcategorias = obtenerSubcategorias($categoriaSeleccionada); ?>
                            <!-- Se reenvía al usuario a la página con la url= coleccion.php?categoria=(categoriaSeleccionada)&subcategoria=(subcategoriaSeleccionada) -->
                            <?php foreach ($subcategorias as $subcategoria): ?>
                                <a href="coleccion.php?categoria=<?php echo $categoriaSeleccionada; ?>&subcategoria=<?php echo $subcategoria; ?>"><?php echo $subcategoria; ?></a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#">Selecciona una categoría primero</a>
                        <?php endif; ?>
                    </section>
                </article>
                <article class="filtro">
                    <a href="coleccion.php" class="reset-boton">Resetear Filtros</a>
                </article>
            </section>
        </section>

        <section class="contenedor-principal">
            <h1>COLECCIÓN DE OBRAS</h1>
            
            <!-- COMPROBACIÓN DE QUE EL ADMINISTRADOR ESTÉ LOGGEADO -->
            <?php 
                if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'Administrador'):
            ?>  
                <!-- Si está loggeado, se muestra el formulario para agregar obra -->
                <section class="formulario-obras">
                    <h2>Agregar Nueva Obra</h2>
                    <form action="coleccion.php" method="POST">
                        <label for="categoria">Categoría:</label>
                        <select id="categoria" name="categoria" required>
                            <option value="Construcciones">Construcciones</option>
                            <option value="Animales">Animales</option>
                            <option value="Paisajes">Paisajes</option>
                        </select><br>
                        
                        <label for="subcategoria">Subcategoría:</label>
                        <input type="text" id="subcategoria" name="subcategoria" required><br>
                        
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required><br>
                        
                        <label for="url">URL de la Imagen:</label>
                        <select id="url" name="url" required>
                            <!-- Se muestran las URLs de las imagenes que hay almacenadas -->
                            <?php foreach ($imagenes as $imagen): ?>
                                <option value="obras_fotos/<?php echo $imagen; ?>">
                                <?php 
                                    echo $imagen; 
                                ?>
                                </option>
                            <?php
                                endforeach;
                            ?>
                        </select><br>
                        <label for="autor">Autor:</label>
                        <input type="text" id="autor" name="autor"><br>
                        
                        <label for="fecha">Fecha:</label>
                        <input type="text" id="fecha" name="fecha"><br>
                        
                        <input type="submit" name="agregar" value="Agregar Obra">
                    </form>
                </section>
                
                <!-- Si $obraSeleccionada es TRUE significará que se ha pulsado sobre el botón "Modificar" correspondiente a una obra -->
                <?php 
                    if ($obraSeleccionada):
                ?>
                    <section class="formulario-obras">
                        <h2>Modificar Obra</h2>
                        <form action="coleccion.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $obraSeleccionada['id']; ?>">
                            
                            <label for="categoria-modificar">Categoría:</label>
                            <select id="categoria-modificar" name="categoria" required>
                                <option value="Construcciones" <?php if ($obraSeleccionada['categoria'] == 'Construcciones') echo 'selected'; ?>>Construcciones</option>
                                <option value="Animales" <?php if ($obraSeleccionada['categoria'] == 'Animales') echo 'selected'; ?>>Animales</option>
                                <option value="Paisajes" <?php if ($obraSeleccionada['categoria'] == 'Paisajes') echo 'selected'; ?>>Paisajes</option>
                            </select><br>
                            
                            <label for="subcategoria-modificar">Subcategoría:</label>
                            <input type="text" id="subcategoria-modificar" name="subcategoria" value="<?php echo $obraSeleccionada['subcategoria']; ?>" required><br>
                            
                            <label for="titulo-modificar">Título:</label>
                            <input type="text" id="titulo-modificar" name="titulo" value="<?php echo $obraSeleccionada['titulo']; ?>" required><br>
                            
                            <label for="url-modificar">URL de la Imagen:</label>
                            <select id="url-modificar" name="url" required>
                                <?php 
                                    foreach ($imagenes as $imagen):
                                ?>
                                    <option value="obras_fotos/<?php echo $imagen; ?>" 
                                        <?php if ($obraSeleccionada['url'] == "obras_fotos/$imagen") echo 'selected'; ?>>
                                            <?php 
                                                echo $imagen; 
                                            ?>
                                    </option>
                                <?php
                                    endforeach; 
                                ?>
                            </select><br>
                            
                            <label for="autor-modificar">Autor:</label>
                            <input type="text" id="autor-modificar" name="autor" value="<?php echo $obraSeleccionada['autor']; ?>"><br>
                            
                            <label for="fecha-modificar">Fecha:</label>
                            <input type="text" id="fecha-modificar" name="fecha" value="<?php echo $obraSeleccionada['fecha']; ?>"><br>
                            
                            <input type="submit" name="modificar" value="Modificar Obra">
                        </form>
                    </section>
                <?php endif; ?>
            <?php endif; ?>
            
            <!-- MOSTRAMOS TODAS LAS OBRAS CONTENIDAS EN LA BASE DE DATOS -->
            <section class="contenedor-obras">
                <?php
                    foreach ($obras as $obra):
                ?>
                    <section class="cada-obra">
                        <a href="obras_documentos/obra1.html">
                            <img src="<?php echo $obra['url']; ?>" alt="<?php echo $obra['titulo']; ?>" class="foto-obra">
                        </a>
                        <h3><?php echo $obra['titulo']; ?></h3>
                        <p>Autor: <?php echo $obra['autor']; ?></p>
                        <p>Fecha: <?php echo $obra['fecha']; ?></p>
                        
                        <!-- Si el administrador está autenticado, podrá utilizar las opciones de "Modificar" y "Eliminar" obra. -->
                        <?php 
                            if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'Administrador'): 
                        ?>
                            <!-- FORMULARIO PARA ELIMINAR UNA OBRA -->
                            <form action="coleccion.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta obra?');">
                                <input type="hidden" name="id" value="<?php echo $obra['id']; ?>">
                                <input type="submit" name="eliminar" value="Eliminar">
                            </form>

                            <!-- FORMULARIO DE SOLICITUD PARA MODIFICAR UNA OBRA 
                                Al seleccionarlo, se procesará un envío get del tipo modificar=2 (para el caso de la obra con ID=2)
                            --> 
                            <form action="coleccion.php" method="GET">
                                <input type="hidden" name="modificar" value="<?php echo $obra['id']; ?>">
                                <input type="submit" value="Modificar">
                            </form>
                        <?php endif; ?>
                    </section>
                <?php endforeach; ?>
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
