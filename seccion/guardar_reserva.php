<?php
include('../db.php');

// Recoger datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : NULL;
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$numero_personas = $_POST['numero_personas'];
$solicitud_especial = !empty($_POST['solicitud_especial']) ? $_POST['solicitud_especial'] : NULL;

// Iniciar sesión para almacenar datos temporalmente
session_start();

try {
    // 1. Insertar cliente si no existe
    $sql_cliente = "SELECT id_cliente FROM cliente WHERE email = ?";
    $stmt = $conn->prepare($sql_cliente);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_cliente = $row['id_cliente'];
    } else {
        $sql_insert_cliente = "INSERT INTO cliente (nombre, email, telefono) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert_cliente);
        $stmt_insert->bind_param("sss", $nombre, $email, $telefono);
        $stmt_insert->execute();
        $id_cliente = $stmt_insert->insert_id;
    }

    // 2. Insertar reserva
    $sql_reserva = "INSERT INTO reserva (id_cliente, fecha, hora, numero_personas, solicitud_especial) 
                    VALUES (?, ?, ?, ?, ?)";
    $stmt_reserva = $conn->prepare($sql_reserva);
    $stmt_reserva->bind_param("issis", $id_cliente, $fecha, $hora, $numero_personas, $solicitud_especial);
    
    if ($stmt_reserva->execute()) {
        // Guardar datos en sesión para la página de confirmación
        $_SESSION['reserva_data'] = [
            'nombre' => $nombre,
            'fecha' => $fecha,
            'hora' => $hora,
            'numero_personas' => $numero_personas,
            'solicitud_especial' => $solicitud_especial
        ];
        
        // Redirigir a página de confirmación
        header("Location: confirmacion_reserva.php");
        exit();
    } else {
        throw new Exception("Error al insertar reserva");
    }
} catch (Exception $e) {
    // Guardar error en sesión
    $_SESSION['reserva_error'] = "Ocurrió un error al procesar tu reserva. Por favor, inténtalo de nuevo.";
    header("Location: ../index.php#reserva");
    exit();
}
?>
<!--session_start():

Crea un ID de sesión único para el usuario (o recupera uno existente)

Permite usar el array $_SESSION para almacenar datos persistentes durante la navegación-->