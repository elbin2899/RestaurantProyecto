<?php
include('../../db.php');

// Incluir PHPMailer
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Recoger datos del formulario
$nombre = trim($_POST['nombre']);
$email = trim($_POST['email']);
$telefono = !empty($_POST['telefono']) ? trim($_POST['telefono']) : NULL;
$fecha = trim($_POST['fecha']);
$hora = trim($_POST['hora']);
$numero_personas = (int)$_POST['numero_personas'];
$solicitud_especial = !empty($_POST['solicitud_especial']) ? trim($_POST['solicitud_especial']) : NULL;

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
        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor SMTP (usando Gmail como ejemplo)
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'jafet409@gmail.com'; // Cambiar por tu email
            $mail->Password = 'tnfr rlbo hhwj fuxk'; // Usar contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            $mail->CharSet = 'UTF-8';
            
            // Configurar email para el CLIENTE
            $mail->setFrom('jafet409@gmail.com', 'Equipo de Soporte');
            $mail->addAddress('jafet409@gmail.com'); // Dirección del usuario registrado
            $mail->addAddress($email, $nombre);
            $mail->Subject = 'Confirmación de reserva en Cuisine X';
            
            // Cuerpo del email en HTML
            $mail->isHTML(true);
            $mail->Body = "
                <h2>¡Reserva Confirmada en Cuisine X!</h2>
                <p>Gracias por reservar con nosotros, $nombre.</p>
                <h3>Detalles de tu reserva:</h3>
                <p><strong>Fecha:</strong> $fecha</p>
                <p><strong>Hora:</strong> $hora</p>
                <p><strong>Número de personas:</strong> $numero_personas</p>
            ";
            
            if (!empty($solicitud_especial)) {
                $mail->Body .= "<p><strong>Solicitud especial:</strong> $solicitud_especial</p>";
            }
            
            $mail->Body .= "
                <p>Si necesitas modificar o cancelar tu reserva, por favor contáctanos.</p>
                <p>¡Esperamos verte pronto!</p>
                <p>El equipo de Restaurante Delicias</p>
            ";

             // Versión alternativa sin HTML
            $mail->AltBody = "Confirmación de reserva\n\n" .
                "Nombre: $nombre\n" .
                "Fecha: $fecha\n" .
                "Hora: $hora\n" .
                "Personas: $numero_personas\n" .
                ($solicitud_especial ? "Solicitud: $solicitud_especial\n" : "") .
                "\n¡Gracias por tu reserva!";
            
            $mail->send();

            // Enviar copia al RESTAURANTE-NO OPERATIVO AÚN
            $mail->clearAddresses();
            $mail->addAddress('reservas@turestaurante.com', 'Administración');
            $mail->Subject = "Nueva reserva - $nombre";
            $mail->Body = "
                <h3>Nueva reserva recibida</h3>
                <p><strong>Cliente:</strong> $nombre</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Teléfono:</strong> " . ($telefono ? $telefono : 'No proporcionado') . "</p>
                <p><strong>Fecha:</strong> $fecha</p>
                <p><strong>Hora:</strong> $hora</p>
                <p><strong>Personas:</strong> $numero_personas</p>
                " . ($solicitud_especial ? "<p><strong>Solicitud especial:</strong> $solicitud_especial</p>" : "");
            
            $mail->send();

        // Redirigir a página de confirmación
            header("Location: confirmacion_reserva.php");
            exit();
            
        } catch (Exception $e) {
            // Guardar error en sesión
            $_SESSION['reserva_error_modal'] = 'Reserva completada pero hubo un error al enviar el correo de confirmación.';
            header("Location: ../../index.php#reserva");
            exit();
        }
    } else {
        throw new Exception("Error al insertar reserva");
    }
} catch (Exception $e) {
    $_SESSION['reserva_error_modal'] = 'Lo sentimos, no hay mesas disponibles para la fecha y hora seleccionadas.';
    header("Location: ../../index.php#reserva");
    exit();
}
?>
<!--session_start():

Crea un ID de sesión único para el usuario (o recupera uno existente)

Permite usar el array $_SESSION para almacenar datos persistentes durante la navegación-->