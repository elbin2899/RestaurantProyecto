<?php
session_start();

// Validar si el usuario ha iniciado sesión y tiene rol 'admin'
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.html"); // Redirige si no tiene acceso
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            text-align: center;
            padding-top: 100px;
        }
        .mensaje {
            background: white;
            display: inline-block;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .mensaje h1 {
            color: #2c3e50;
        }
        .mensaje i {
            color: #3498db;
            font-size: 50px;
        }
       
        .btn-cerrar {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-cerrar:hover {
            background-color: #c0392b;
        }
</style>

    </style>
</head>
<body>
    <div class="mensaje">
        <i class="fas fa-user-shield"></i>
        <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h1>
        <p>Has ingresado correctamente al panel del administrador.</p>
        <a href="carta_restaurante.php" class="btn btn-outline-dark my-3">
        <i class="fas fa-utensils"></i> Ver / Editar Carta
    </div>
    
</a>

    <p><a href="logout.php" class="btn-cerrar">Cerrar sesión</a></p>

</body>
</html>

