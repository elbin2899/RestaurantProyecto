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
    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
       body {
                margin: 0;
                padding: 0;
                background: linear-gradient(rgba(15, 23, 43, 0.9), rgba(15, 23, 43, 0.9)), url('../../img/bg-hero.jpg');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                font-family: 'Quicksand', sans-serif;
                color: #fff;
                text-align: center;
                padding: 30px 10px;
                min-height: 100vh;
            }

            h2 {
                color: #fff;
                font-size: 1.8rem;
                margin-bottom: 25px;
            }

            .card {
                background-color: #ffffff;
                color: #333;
                padding: 20px;
                border-radius: 10px;
                max-width: 500px;
                margin: 0 auto 30px auto;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
                font-size: 0.95rem;
            }

            .card-title {
                font-weight: 600;
                font-size: 1.1rem;
                color: #0F172B;
            }

            .card p {
                margin-bottom: 10px;
                color: #2c3e50;
            }

            footer p {
                color: #fff;
                font-size: 1rem;
                margin-bottom: 10px;
            }

            .btn-home {
                background-color: #fea116;
                color: white;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 600;
                transition: background-color 0.3s ease;
                display: inline-block;
                margin-top: 10px;
            }

            .btn-home:hover {
                background-color: #e6950b;
            }
            .icono-check-container {
                font-size: 60px;
                color:rgb(105, 167, 233); 
                margin-bottom: 15px;
            }
            .confirmation-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: #FFA500;
                margin-bottom: 20px;
                font-family: 'Nunito', sans-serif;
            }
            .confirmation-message {
                color: #ddd;
                font-size: 1.1rem;
                margin-top: 25px;
                font-style: italic;
            }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirmation-container text-center">
            <div class="icono-check-container">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <h1 class="confirmation-title">¡Reserva Confirmada!</h1>
            
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
            
            <p class="confirmation-message">Hemos enviado un correo de confirmación a tu dirección de email.</p>
            
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