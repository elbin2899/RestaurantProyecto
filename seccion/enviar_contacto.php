<?php
// Configuración de PHPMailer
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Validar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact_seccion.php');
    exit;
}

// Validar campos requeridos
$required_fields = ['name', 'email', 'subject', 'message'];
foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        header('Location: contact_seccion.php?error='.urlencode('Todos los campos son requeridos'));
        exit;
    }
}

// Sanitizar entradas
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
$message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: contact_seccion.php?error='.urlencode('Email no válido'));
    exit;
}

// Crear instancia de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '';
    $mail->Password = '';
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

    // Configurar remitente y destinatario
    $mail->setFrom($email, $name);
    $mail->addAddress(''); // Tu dirección de correo
    
    // Asunto y cuerpo del mensaje
    $mail->Subject = $subject;
    $mail->Body = "Nuevo mensaje de contacto:\n\n" .
                 "Nombre: $name\n" .
                 "Email: $email\n" .
                 "Asunto: $subject\n" .
                 "Mensaje:\n$message";
    
    // Enviar el correo
    $mail->send();
    
    // Redirigir con mensaje de éxito
    // Usa una URL absoluta para evitar problemas
    $base_url = 'http://'.$_SERVER['HTTP_HOST'].'/restaurantProyecto/contact.php';
    header('Location: '.$base_url.'?success=Mensaje+enviado+correctamente');
    exit;
    
} catch (Exception $e) {
    // Redirigir con mensaje de error
    header('Location: contact_seccion.php?error='.urlencode('Error al enviar el mensaje: '.$e->getMessage()));
    exit;
}
?>
