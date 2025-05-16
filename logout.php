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

