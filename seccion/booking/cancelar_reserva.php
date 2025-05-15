<?php
include('../../db.php');

// Incluir PHPMailer (necesario para enviar el correo de confirmación)
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar si se proporcionó el token
if (!isset($_GET['token'])) {
    die("Token de cancelación no proporcionado.");
}

$token = $_GET['token'];

// Buscar la reserva con este token (incluyendo el email del cliente)
$sql = "SELECT r.id_reserva, c.email, c.nombre, r.fecha, r.hora 
        FROM reserva r 
        JOIN cliente c ON r.id_cliente = c.id_cliente 
        WHERE r.token_cancelacion = ? AND r.estado = 'confirmada'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Reserva no encontrada o ya cancelada.");
}

$reserva = $result->fetch_assoc();
$id_reserva = $reserva['id_reserva'];
$email_cliente = $reserva['email'];
$nombre_cliente = $reserva['nombre'];
$fecha_reserva = $reserva['fecha'];
$hora_reserva = $reserva['hora'];

// Mostrar formulario para motivo de cancelación
if (!isset($_POST['motivo'])) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cancelar Reserva</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h4>Cancelar Reserva</h4>
                        </div>
                        <div class="card-body">
                            <p>¿Estás seguro que deseas cancelar tu reserva para el día <?= htmlspecialchars($fecha_reserva) ?> a las <?= htmlspecialchars($hora_reserva) ?>?</p>
                            <form method="POST">
                                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                                <div class="mb-3">
                                    <label for="motivo" class="form-label">Motivo de cancelación:</label>
                                    <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                                <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Procesar la cancelación
$motivo = trim($_POST['motivo']);

// Actualizar la reserva en la base de datos
$sql_update = "UPDATE reserva SET 
               estado = 'cancelada', 
               motivo_cancelacion = ?,
               token_cancelacion = NULL 
               WHERE id_reserva = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("si", $motivo, $id_reserva);

if ($stmt_update->execute()) {
    // Configurar y enviar email de confirmación de cancelación
    $mail = new PHPMailer(true);
    try {
        // Configuración del servidor SMTP (usa la misma que en guardar_reserva.php)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jafet409@gmail.com'; // Tu email
        $mail->Password = 'tnfr rlbo hhwj fuxk'; // Tu contraseña de aplicación
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
        
        // Configurar email
        $mail->setFrom('jafet409@gmail.com', 'Restaurante Cuisine X');
        $mail->addAddress($email_cliente, $nombre_cliente);
        $mail->Subject = 'Confirmación de cancelación de reserva';
        
        // Cuerpo del email en HTML
        $mail->isHTML(true);
        $mail->Body = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Cancelación de Reserva</title>
            <style>
                body {
                    font-family: "Helvetica Neue", Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background-color: #f9f9f9;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background: #ffffff;
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }
                .header {
                    background-color: #e74c3c;
                    color: white;
                    padding: 30px 20px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .content {
                    padding: 30px;
                }
                .reservation-details {
                    background: #f8f9fa;
                    border-radius: 6px;
                    padding: 20px;
                    margin-bottom: 25px;
                }
                .detail-item {
                    margin-bottom: 10px;
                    display: flex;
                }
                .detail-label {
                    font-weight: bold;
                    min-width: 150px;
                    color: #555;
                }
                .footer {
                    background-color: #f1f1f1;
                    padding: 20px;
                    text-align: center;
                    font-size: 14px;
                    color: #666;
                }
                .button {
                    display: inline-block;
                    padding: 12px 24px;
                    background-color: #1a5276;
                    color: white;
                    text-decoration: none;
                    border-radius: 4px;
                    font-weight: bold;
                    margin-top: 15px;
                }
                .logo {
                    max-width: 150px;
                    margin-bottom: 20px;
                }
                .reason-box {
                    background: #fff8f8;
                    border-left: 4px solid #e74c3c;
                    padding: 15px;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>Cancelación de Reserva Confirmada</h1>
                </div>
                
                <div class="content">
                    <p>Hola '.$nombre_cliente.',</p>
                    <p>Hemos procesado la cancelación de tu reserva en Restaurante Cuisine X.</p>
                    
                    <div class="reservation-details">
                        <div class="detail-item">
                            <span class="detail-label">Fecha original:</span>
                            <span>'.$fecha_reserva.'</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Hora:</span>
                            <span>'.$hora_reserva.'</span>
                        </div>
                    </div>
                    
                    <div class="reason-box">
                        <p><strong>Motivo indicado:</strong></p>
                        <p>'.$motivo.'</p>
                    </div>
                    
                    <p>Si esto fue un error o deseas hacer una nueva reserva, puedes contactarnos directamente:</p>
                    <a href="tel:+34639877214" class="button">
                        Llamar al Restaurante
                    </a>
                    
                    <p style="margin-top: 30px;">¡Esperamos verte pronto!</p>
                </div>
                
                <div class="footer">
                    <p>Restaurante Cuisine X<br>
                    Dirección: Calle Principal 123, Ciudad<br>
                    Teléfono: +34 639 877 214</p>
                    <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
                </div>
            </div>
        </body>
        </html>';
        
        // Versión alternativa sin HTML
        $mail->AltBody = "Cancelación de Reserva Confirmada\n\n" .
            "Hola $nombre_cliente,\n\n" .
            "Hemos procesado la cancelación de tu reserva para el $fecha_reserva a las $hora_reserva.\n" .
            "Motivo indicado: $motivo\n\n" .
            "Si esto fue un error o deseas hacer una nueva reserva, por favor contáctanos.\n\n" .
            "Gracias,\n" .
            "El equipo de Restaurante Cuisine X";
        
        $mail->send();
        
        // Mostrar confirmación al usuario
        echo '<script>
                alert("Tu reserva ha sido cancelada. Se ha enviado un correo de confirmación a tu dirección.");
                window.location.href = "../../index.php";
              </script>';
    } catch (Exception $e) {
        // Si falla el envío del correo, igual se cancela la reserva pero mostramos un mensaje
        error_log("Error al enviar correo de cancelación: " . $e->getMessage());
        echo '<script>
                alert("Tu reserva ha sido cancelada, pero hubo un error al enviar el correo de confirmación.");
                window.location.href = "../../index.php";
              </script>';
    }
} else {
    die("Error al cancelar la reserva: " . $conn->error);
}
?>