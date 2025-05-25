<?php
session_start();

if (!isset($_SESSION['reserva_data'])) {
    header("Location: ../../index.php");
    exit();
}

$reserva = $_SESSION['reserva_data'];
unset($_SESSION['reserva_data']);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Confirmación de reserva</title>

    <!-- Fuentes de Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap"
        rel="stylesheet">

    <!-- Iconos y librerías -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Estilos -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <link rel="stylesheet" href="confirmacion.css">
</head>

<body>
    <div class=" container-xxl py-5 bg-dark hero-header mb-5 confirmation-wrapper">
        <div class="confirmation-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="confirmation-title">¡Reserva Confirmada!</h1>

        <div class="confirmation-box text-start">
            <h5 style="margin-top: 1%;">Detalles de tu reserva</h5>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($reserva['nombre']) ?></p>
            <p><strong>Fecha:</strong> <?= htmlspecialchars($reserva['fecha']) ?></p>
            <p><strong>Hora:</strong> <?= htmlspecialchars($reserva['hora']) ?></p>
            <p><strong>Número de personas:</strong> <?= htmlspecialchars($reserva['numero_personas']) ?></p>
            <?php if (!empty($reserva['solicitud_especial'])): ?>
            <p><strong>Solicitud especial:</strong> <?= htmlspecialchars($reserva['solicitud_especial']) ?></p>
            <?php endif; ?>
        </div>

        <p class="confirmation-message">Hemos enviado un correo de confirmación a tu dirección de email.</p>

        <a href="../../index.php" class="btn-home">
            <i class="fas fa-home me-2"></i>Volver al inicio
        </a>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
