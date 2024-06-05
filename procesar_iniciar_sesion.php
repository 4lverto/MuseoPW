<?php

session_start();
include 'conexion_bd.inc';

try {
    // Crear conexión PDO
    $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar el modo de error de PDO a excepción
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    // Preparar y ejecutar la consulta
    $sentenciaSQL = $conexion->prepare("SELECT * FROM usuarios WHERE nombre = :nombre AND password = :password");
    $sentenciaSQL->bindParam(':nombre', $nombre);
    $sentenciaSQL->bindParam(':password', $password);

    $sentenciaSQL->execute();

    // Verificar si se encontró el usuario
    if ($sentenciaSQL->rowCount() > 0) {
        $usuario = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['tipo'] = $usuario['tipo'];

        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Usuario o contraseña incorrectos";
        header("Location: index.php");
        exit();
    }
    
} catch(PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    
    header("Location: index.php");
    exit();
}

$conexion = null;
?>
