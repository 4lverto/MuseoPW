<?php
// registrar_usuario.php
$servername = "localhost";
$username = "pwalbertoov";
$password = "23albertoov24";
$dbname = "dbalbertoov_pw2324";

try {
    // Crear conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Contraseña sin encriptar
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];

    // Preparar y ejecutar la inserción de datos
    $stmt = $conn->prepare("INSERT INTO usuarios (email, nombre, password, edad, genero) VALUES (:email, :nombre, :password, :edad, :genero)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':edad', $edad);
    $stmt->bindParam(':genero', $genero);

    $stmt->execute();

    // Redirigir al usuario a index.php después del registro
    header("Location: index.php");
    exit();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
