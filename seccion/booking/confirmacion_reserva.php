<?php
session_start();

// Verificar si hay datos de reserva
if (!isset($_SESSION['reserva_data'])) {
    header("Location: ../../index.php");
    exit();
}

// Obtener datos de la reserva
$reserva = $_SESSION['reserva_data'];
unset($_SESSION['reserva_data']); // Limpiar la sesión
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        .confirmation-container {
            max-width: 700px; /* Aumentado para acomodar texto más grande */
            margin: 50px auto;
            padding: 40px; /* Más padding */
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .confirmation-icon {
            font-size: 6rem; /* Aumentado de 5rem */
            color: rgb(5, 54, 109);
            margin-bottom: 30px; /* Más espacio */
        }
        
        /* Tipografía aumentada */
        h2 {
            font-size: 2.5rem; /* Aumentado considerablemente */
            margin-bottom: 30px !important;
        }
        
        .card-title {
            font-size: 1.8rem; /* Aumentado */
            margin-bottom: 25px;
        }
        
        .card-body p {
            font-size: 1.3rem; /* Texto más grande */
            margin-bottom: 15px;
        }
        
        .card-body strong {
            font-size: 1.3rem; /* Texto en negrita más grande */
        }
        
        .mb-4 {
            font-size: 1.4rem; /* Texto del correo más grande */
        }
        
        .btn {
            font-size: 1.3rem; /* Botón más grande */
            padding: 12px 30px;
        }
        
        .btn i {
            font-size: 1.3rem; /* Icono del botón más grande */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirmation-container text-center">
            <div class="confirmation-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="mb-4">¡Reserva Confirmada!</h2>
            
            <div class="card mb-4">
                <div class="card-body text-start">
                    <h5 class="card-title">Detalles de tu reserva</h5>
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($reserva['nombre']) ?></p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($reserva['fecha']) ?></p>
                    <p><strong>Hora:</strong> <?= htmlspecialchars($reserva['hora']) ?></p>
                    <p><strong>Número de personas:</strong> <?= htmlspecialchars($reserva['numero_personas']) ?></p>
                    <?php if (!empty($reserva['solicitud_especial'])): ?>
                        <p><strong>Solicitud especial:</strong> <?= htmlspecialchars($reserva['solicitud_especial']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <p class="mb-4">Hemos enviado un correo de confirmación a tu dirección de email.</p>
            
            <a href="../../index.php" class="btn btn-primary">
                <i class="fas fa-home"></i> Volver al inicio
            </a>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/js/all.min.js"></script>
</body>
</html>