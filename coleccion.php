<?php
session_start();

// Conectar a la base de datos
$servername = "localhost";
$username = "pwalbertoov";
$password = "23albertoov24";
$dbname = "dbalbertoov_pw2324";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}

// Funciones para gestionar obras
function obtenerObras($categoria = null, $subcategoria = null) {
    global $conn;
    $sql = "SELECT * FROM obras";
    if ($categoria && $subcategoria) {
        $sql .= " WHERE categoria = :categoria AND subcategoria = :subcategoria";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':categoria', $categoria);
        $stmt->bindParam(':subcategoria', $subcategoria);
    } elseif ($categoria) {
        $sql .= " WHERE categoria = :categoria";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':categoria', $categoria);
    } else {
        $stmt = $conn->prepare($sql);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerCategorias() {
    global $conn;
    $sql = "SELECT DISTINCT categoria FROM obras";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function obtenerSubcategorias($categoria) {
    global $conn;
    $sql = "SELECT DISTINCT subcategoria FROM obras WHERE categoria = :categoria";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function obtenerImagenes() {
    $dir = 'obras_fotos/';
    $imagenes = array_diff(scandir($dir), array('..', '.'));
    return $imagenes;
}

function obtenerObra($id) {
    global $conn;
    $sql = "SELECT * FROM obras WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function agregarObra($categoria, $subcategoria, $titulo, $url, $autor, $fecha) {
    global $conn;
    $sql = "INSERT INTO obras (categoria, subcategoria, titulo, url, autor, fecha) VALUES (:categoria, :subcategoria, :titulo, :url, :autor, :fecha)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':subcategoria', $subcategoria);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
}

function modificarObra($id, $categoria, $subcategoria, $titulo, $url, $autor, $fecha) {
    global $conn;
    $sql = "UPDATE obras SET categoria = :categoria, subcategoria = :subcategoria, titulo = :titulo, url = :url, autor = :autor, fecha = :fecha WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':subcategoria', $subcategoria);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->execute();
}

function eliminarObra($id) {
    global $conn;
    $sql = "DELETE FROM obras WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

// Gestión de solicitudes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['agregar'])) {
        agregarObra($_POST['categoria'], $_POST['subcategoria'], $_POST['titulo'], $_POST['url'], $_POST['autor'], $_POST['fecha']);
    } elseif (isset($_POST['eliminar'])) {
        eliminarObra($_POST['id']);
    } elseif (isset($_POST['modificar'])) {
        modificarObra($_POST['id'], $_POST['categoria'], $_POST['subcategoria'], $_POST['titulo'], $_POST['url'], $_POST['autor'], $_POST['fecha']);
    }
}

$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$subcategoriaSeleccionada = isset($_GET['subcategoria']) ? $_GET['subcategoria'] : null;
$obraSeleccionada = isset($_GET['modificar']) ? obtenerObra($_GET['modificar']) : null;

$obras = obtenerObras($categoriaSeleccionada, $subcategoriaSeleccionada);
$categorias = obtenerCategorias();
$imagenes = obtenerImagenes();
?>

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
                <li><a class="enlace-nav" href="visita.php">Visita</a></li>
                <li><a class="enlace-nav" href="exposiciones.php">Exposiciones</a></li>
                <li><a class="enlace-nav" href="informacion.php">Información</a></li>
                <li><a class="enlace-nav" href="experiencias.php">Experiencias</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="contenedor-filtros">
            <h2 class="filtrar-obras">Filtrar Obras</h2>
            <div class="cada-filtro">
                <div class="filtro">
                    <button class="drop-boton">Categoría</button>
                    <div class="drop-contenido">
                        <?php foreach ($categorias as $categoria): ?>
                            <a href="coleccion.php?categoria=<?php echo $categoria; ?>"><?php echo $categoria; ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="filtro">
                    <button class="drop-boton">Subcategoría</button>
                    <div class="drop-contenido">
                        <?php if ($categoriaSeleccionada): ?>
                            <?php $subcategorias = obtenerSubcategorias($categoriaSeleccionada); ?>
                            <?php foreach ($subcategorias as $subcategoria): ?>
                                <a href="coleccion.php?categoria=<?php echo $categoriaSeleccionada; ?>&subcategoria=<?php echo $subcategoria; ?>"><?php echo $subcategoria; ?></a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <a href="#">Selecciona una categoría primero</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="filtro">
                    <a href="coleccion.php" class="reset-boton">Resetear Filtros</a>
                </div>
            </div>
        </section>

        <section class="contenedor-principal">
            <h1>COLECCIÓN DE OBRAS</h1>

            <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'Administrador'): ?>
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
                        <select id="subcategoria" name="subcategoria" required>
                            <!-- Add JavaScript to dynamically populate this based on category selection -->
                        </select><br>
                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required><br>
                        <label for="url">URL de la Imagen:</label>
                        <select id="url" name="url" required>
                            <?php foreach ($imagenes as $imagen): ?>
                                <option value="obras_fotos/<?php echo $imagen; ?>"><?php echo $imagen; ?></option>
                            <?php endforeach; ?>
                        </select><br>
                        <label for="autor">Autor:</label>
                        <input type="text" id="autor" name="autor"><br>
                        <label for="fecha">Fecha:</label>
                        <input type="text" id="fecha" name="fecha"><br>
                        <input type="submit" name="agregar" value="Agregar Obra">
                    </form>
                </section>

                <?php if ($obraSeleccionada): ?>
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
                            <select id="subcategoria-modificar" name="subcategoria" required>
                                <!-- Add JavaScript to dynamically populate this based on category selection -->
                            </select><br>
                            <label for="titulo-modificar">Título:</label>
                            <input type="text" id="titulo-modificar" name="titulo" value="<?php echo $obraSeleccionada['titulo']; ?>" required><br>
                            <label for="url-modificar">URL de la Imagen:</label>
                            <select id="url-modificar" name="url" required>
                                <?php foreach ($imagenes as $imagen): ?>
                                    <option value="obras_fotos/<?php echo $imagen; ?>" <?php if ($obraSeleccionada['url'] == "obras_fotos/$imagen") echo 'selected'; ?>><?php echo $imagen; ?></option>
                                <?php endforeach; ?>
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

            <div class="contenedor-obras">
                <?php foreach ($obras as $obra): ?>
                    <div class="cada-obra">
                        <a href="obras_documentos/obra1.html">
                            <img src="<?php echo $obra['url']; ?>" alt="<?php echo $obra['titulo']; ?>" class="foto-obra">
                        </a>
                        <h3><?php echo $obra['titulo']; ?></h3>
                        <p>Autor: <?php echo $obra['autor']; ?></p>
                        <p>Fecha: <?php echo $obra['fecha']; ?></p>
                        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] == 'Administrador'): ?>
                            <form action="coleccion.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta obra?');">
                                <input type="hidden" name="id" value="<?php echo $obra['id']; ?>">
                                <input type="submit" name="eliminar" value="Eliminar">
                            </form>
                            <form action="coleccion.php" method="GET">
                                <input type="hidden" name="modificar" value="<?php echo $obra['id']; ?>">
                                <input type="submit" value="Modificar">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <a class="comosehizo" href="pie_pagina/como_se_hizo.pdf">¿Cómo se hizo?</a>
        <a class="contact" href="pie_pagina/contacto.html">Contacto</a>
        <p>Derechos de autor © 2024 Museo Mojang.</p>
    </footer>

    <script>
        function populateSubcategories(selectElement, subcategorySelectElement) {
            var categoria = selectElement.value;
            subcategorySelectElement.innerHTML = '';
            if (categoria === 'Construcciones') {
                subcategorySelectElement.innerHTML += '<option value="Piedra">Piedra</option>';
                subcategorySelectElement.innerHTML += '<option value="Madera">Madera</option>';
                subcategorySelectElement.innerHTML += '<option value="Metal">Metal</option>';
            } else if (categoria === 'Animales') {
                subcategorySelectElement.innerHTML += '<option value="Mamiferos">Mamíferos</option>';
                subcategorySelectElement.innerHTML += '<option value="Aves">Aves</option>';
                subcategorySelectElement.innerHTML += '<option value="Monstruos">Monstruos</option>';
            } else if (categoria === 'Paisajes') {
                subcategorySelectElement.innerHTML += '<option value="Nocturnos">Nocturnos</option>';
                subcategorySelectElement.innerHTML += '<option value="Veraniegos">Veraniegos</option>';
                subcategorySelectElement.innerHTML += '<option value="Bonitos">Bonitos</option>';
            }
        }

        document.getElementById('categoria').addEventListener('change', function() {
            populateSubcategories(this, document.getElementById('subcategoria'));
        });

        document.getElementById('categoria-modificar').addEventListener('change', function() {
            populateSubcategories(this, document.getElementById('subcategoria-modificar'));
        });

        // Populate subcategories on page load for modification form
        if (document.getElementById('categoria-modificar').value) {
            populateSubcategories(document.getElementById('categoria-modificar'), document.getElementById('subcategoria-modificar'));
            document.getElementById('subcategoria-modificar').value = '<?php echo $obraSeleccionada['subcategoria']; ?>';
        }
    </script>
</body>
</html>
