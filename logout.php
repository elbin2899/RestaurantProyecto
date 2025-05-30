<?php
session_start();
session_unset();     // Elimina todas las variables de sesión
session_destroy();   // Destruye la sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sesión cerrada</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
       body {
                margin: 0;
                padding: 0;
                background: linear-gradient(rgba(15, 23, 43, 0.9), rgba(15, 23, 43, 0.9)), url('img/bg-hero.jpg');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
                font-family: 'Quicksand', sans-serif;
                color: #fff;
                text-align: center;
                padding: 30px 10px;
                min-height: 100vh;
            }
    </style>
</head>
<body>

<script>
Swal.fire({
    icon: 'info',
    title: 'Sesión cerrada',
    text: 'Has cerrado sesión correctamente.',
    showConfirmButton: false,
    timer: 2500
}).then(() => {
    window.location.href = 'index.php';
});
</script>

</body>
</html>

