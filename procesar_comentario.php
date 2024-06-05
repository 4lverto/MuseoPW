<?php
    session_start();
    include 'conexion_bd.inc';

    // Compruebo si el usuario se ha autentificado en el museo.
    if (!isset($_SESSION['nombre'])) {
        header("Location: index.php");
        exit();
    }

    try {
        // Crear conexión PDO
        $conexion = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Obtener el correo electrónico y tipo del usuario
        $sentenciaSQL = $conexion->prepare("SELECT email, tipo FROM usuarios WHERE nombre = :nombre");
        $sentenciaSQL->bindParam(':nombre', $_SESSION['nombre']);
        $sentenciaSQL->execute();
       
        $usuario = $sentenciaSQL->fetch(PDO::FETCH_ASSOC);
        
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
        $sentenciaSQL = $conexion->prepare("INSERT INTO comentarios (email, comentario, valoracion) VALUES (:email, :comentario, :valoracion)");
        $sentenciaSQL->bindParam(':email', $email);
        $sentenciaSQL->bindParam(':comentario', $comentarios);
        $sentenciaSQL->bindParam(':valoracion', $valoracion);
        $sentenciaSQL->execute();

        // Redirigir de nuevo a la página de experiencias
        header("Location: experiencias.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conexion = null;
?>
