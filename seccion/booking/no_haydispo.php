<?php
session_start();

if (!isset($_SESSION['reserva_error'])) {
    header("Location: ../../index.php");
    exit();
}

$mensaje_error = $_SESSION['reserva_error'];
unset($_SESSION['reserva_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva No Disponible</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .error-container {
            max-width: 500px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
            border-top: 4px solid #dc3545;
        }
        .error-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffe6e8;
            border-radius: 50%;
        }
        .error-icon i {
            font-size: 40px;
            color: #dc3545;
        }
        h2 {
            font-size: 1.5rem;
            color: #343a40;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .error-message {
            font-size: 1rem;
            color: #495057;
            margin-bottom: 30px;
            line-height: 1.5;
            padding: 0 15px;
        }
        .btn {
            font-size: 0.9rem;
            padding: 8px 20px;
            border-radius: 4px;
            font-weight: 500;
        }
        .logo {
            margin-bottom: 25px;
        }
        .logo img {
            height: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            
            <div class="error-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            
            <h2>Disponibilidad no confirmada</h2>
            
            <div class="error-message">
                <?= htmlspecialchars($mensaje_error) ?>
                <p class="mt-3">Por favor, intente con otra fecha, hora o número de comensales.</p>
            </div>
            
            <a href="../index.php#reserva" class="btn btn-outline-primary">
                <i class="fas fa-undo-alt me-2"></i> Volver al formulario
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>