<?php 
session_start();

include 'db.php';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['contrasena']);

    // Validar que no estén vacíos
    if (empty($usuario) || empty($password)) {
        echo '<div class="error-message">Por favor, ingresa tu usuario y contraseña.</div>';
        exit;
    }

    // Buscar al usuario por nombre de usuario
    $sql = "SELECT id, nombre_usuario, contrasena, rol FROM usuario WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    // ¿Existe el usuario?
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre_usuario, $hashedPassword, $rol);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($password, $hashedPassword)) {
            // Iniciar sesión
            $_SESSION['id'] = $id;
            $_SESSION['usuario'] = $nombre_usuario;
            $_SESSION['rol'] = $rol;

            // Redirigir según el rol
            if ($rol === 'admin' || $rol === 'empleado') {
                header("Location: administrador.php");
            } else {
                header("Location: index.php");
            }            
            exit;
        } else {
            echo '<div class="error-message">Contraseña incorrecta. <a href="index.php">Volver</a></div>';
        }
    } else {
        echo '<div class="error-message">Usuario no encontrado. <a href="index.php">Volver</a></div>';
    }

    // Cerrar recursos
    $stmt->close();
    $conn->close();
}
?>
