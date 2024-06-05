<?php
session_start();
include 'conexion_bd.inc';

// Verificación de que la solicitud que se recibe es POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Almaceno los datos enviados en variables
        $email = $_POST['email'];
        $nombre = $_POST['nombre'];
        $password = $_POST['password'];
        $edad = $_POST['edad'];
        $genero = $_POST['genero'];
        $tipo = "Publico General";
        
        // Defino la consulta genérica colocando marcadores de parámetros
        $consultaSQL = "INSERT INTO usuarios (email, nombre, password, edad, genero, tipo) 
                        VALUES (:email, :nombre, :password, :edad, :genero, :tipo)";
        
        // Obtengo un objeto de la clase PDOStatement ($sentenciaSQL), mediante el método "prepare" del objeto conexión.
        $sentenciaSQL = $conexion->prepare($consultaSQL);
        
        // Asigno los valores correspondientes a los marcadores de parámetros
        $sentenciaSQL->bindParam(':email', $email);
        $sentenciaSQL->bindParam(':nombre', $nombre);
        $sentenciaSQL->bindParam(':password', $password);
        $sentenciaSQL->bindParam(':edad', $edad);
        $sentenciaSQL->bindParam(':genero', $genero);
        $sentenciaSQL->bindParam(':tipo', $tipo);

        // Ejecuto la sentencia
        $sentenciaSQL->execute();
        header("Location: index.php");
        exit();

    } catch(PDOException $e) {
        die("Conexión fallida: " . $e->getMessage());
    }
}
?>
