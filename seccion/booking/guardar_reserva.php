<?php
include('../../db.php');

// Iniciar sesión para almacenar datos temporalmente
session_start();

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
     $sql_reserva = "INSERT INTO reserva (id_cliente, fecha, hora, numero_personas, solicitud_especial, estado, token_cancelacion) 
                        VALUES (?, ?, ?, ?, ?, 'confirmada', ?)";
    $stmt_reserva = $conn->prepare($sql_reserva);

    // Generar token único para cancelación
    $token_cancelacion = bin2hex(random_bytes(32));
    $stmt_reserva->bind_param("ississ", $id_cliente, $fecha, $hora, $numero_personas, $solicitud_especial, $token_cancelacion);
    
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
           $mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #1a5276;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 25px;
        }
        h2.email-title {
            color: #ffffff !important;
            font-size: 24px;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }
        h3 {
            color: #1a5276;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .reservation-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .cancel-link {
            display: inline-block;
            margin: 15px 0;
            padding: 10px 15px;
            background-color: #dc3545;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>¡Reserva Confirmada en Cuisine X!</h2>
        </div>
        
        <div class="content">
            <p>Gracias por reservar con nosotros, '.$nombre.'.</p>
            
            <h3>Detalles de tu reserva:</h3>
            
            <div class="reservation-details">
                <p><strong>Fecha:</strong> '.$fecha.'</p>
                <p><strong>Hora:</strong> '.$hora.'</p>
                <p><strong>Número de personas:</strong> '.$numero_personas.'</p>
                '.(!empty($solicitud_especial) ? "<p><strong>Solicitud especial:</strong> ".$solicitud_especial."</p>" : "").'
            </div>
            
            <p>Si necesitas modificar o cancelar tu reserva, por favor usa este enlace:</p>
            
            <a href="http://localhost/restaurantProyecto/seccion/booking/cancelar_reserva.php?token='.$token_cancelacion.'" 
               class="cancel-link">
               Cancelar esta reserva
            </a>
            
            <p>¡Esperamos verte pronto!</p>
        </div>
        
        <div class="footer">
            <p>El equipo de Cuisine X</p>
            <p>Teléfono: +34 639 877 214</p>
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>';

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
            $mail->addAddress('', 'Administración');
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

       // Redirigir a página de confirmación aunque el correo falle
    header("Location: confirmacion_reserva.php");
    exit();

} catch (Exception $e) {
    // Registrar el error de correo si quieres
    error_log("Error al enviar correo: " . $mail->ErrorInfo);

    // Igual redirigir a confirmación (aunque sin correo enviado)
    header("Location: confirmacion_reserva.php?correo=fallo");
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