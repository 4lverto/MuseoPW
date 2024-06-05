<?php
// Conectar a la base de datos
$servername = "localhost";
$username = "pwalbertoov";
$password = "23albertoov24";
$dbname = "dbalbertoov_pw2324";

try {
    $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Conexión fallida: " . $e->getMessage());
}
?>
