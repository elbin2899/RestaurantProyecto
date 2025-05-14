<?php
include('../../db.php');

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
    // 1. Verificar disponibilidad de mesas
    // Buscar la mesa más pequeña posible que pueda acomodar a esa cantidad de personas
    $consulta_capacidad = "
    SELECT capacidad FROM mesa 
    WHERE capacidad >= ? 
    ORDER BY capacidad ASC 
    LIMIT 1
    ";
    $stmt1 = $conn->prepare($consulta_capacidad);
    $stmt1->bind_param("i", $numero_personas);
    $stmt1->execute();
    $res1 = $stmt1->get_result();

    if ($res1->num_rows === 0) {
        throw new Exception('No hay mesas adecuadas disponibles.');
    }

    $capacidad_minima = $res1->fetch_assoc()['capacidad'];

    // 1. Total de mesas con esa capacidad
    $sql_total_mesas = "SELECT COUNT(*) as total FROM mesa WHERE capacidad = ?";
    $stmt2 = $conn->prepare($sql_total_mesas);
    $stmt2->bind_param("i", $capacidad_minima);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $total_mesas = $res2->fetch_assoc()['total'];

    // 2. Cuántas de esas ya están ocupadas en esa fecha y hora
    $sql_reservadas = "
    SELECT COUNT(*) as total FROM reserva r
    INNER JOIN mesa m ON r.id_mesa = m.id_mesa
    WHERE m.capacidad = ? AND r.fecha = ? AND r.hora = ? AND r.id_mesa IS NOT NULL
    ";
    $stmt3 = $conn->prepare($sql_reservadas);
    $stmt3->bind_param("iss", $capacidad_minima, $fecha, $hora);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    $mesas_ocupadas = $res3->fetch_assoc()['total'];

    // Comparar
    if ($mesas_ocupadas >= $total_mesas) {
        throw new Exception("No hay mesas disponibles para $numero_personas personas en la fecha y hora solcitada.");
    }

    // 2. Insertar cliente si no existe
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

    // 3. Insertar reserva
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
    $_SESSION['reserva_error_modal'] = 'Lo sentimos, no hay mesas disponibles para la fecha y hora seleccionadas.';
header("Location: ../../index.php#reserva");
exit();
}
?>
<!--session_start():

Crea un ID de sesión único para el usuario (o recupera uno existente)

Permite usar el array $_SESSION para almacenar datos persistentes durante la navegación-->