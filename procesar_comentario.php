<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "pwalbertoov";
$password = "23albertoov24";
$dbname = "dbalbertoov_pw2324";

try {
    // Crear conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el correo electrónico y tipo del usuario
    $stmt = $conn->prepare("SELECT email, tipo FROM usuarios WHERE nombre = :nombre");
    $stmt->bindParam(':nombre', $_SESSION['nombre']);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $email = $usuario['email'];
    $tipo = $usuario['tipo'];

    // Verificar el tipo de usuario
    if ($tipo == 'Administrador') {
        $_SESSION['error'] = "Eres el administrador, tú no puedes realizar comentarios sobre experiencias.";
        header("Location: experiencias.php");
        exit();
    }

    // Obtener datos del formulario
    $valoracion = $_POST['valoracion'];
    $comentarios = $_POST['comentarios'];

    // Insertar comentario en la base de datos
    $stmt = $conn->prepare("INSERT INTO comentarios (email, comentario, valoracion) VALUES (:email, :comentario, :valoracion)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':comentario', $comentarios);
    $stmt->bindParam(':valoracion', $valoracion);
    $stmt->execute();

    // Redirigir de nuevo a la página de experiencias
    header("Location: experiencias.php");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
