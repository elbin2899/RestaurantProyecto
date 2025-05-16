<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body onload="mostrarAlerta()">

<div class="container mt-5 text-center">
    <h1 class="display-1 text-danger">404</h1>
    <h2 class="mb-4">Página no encontrada</h2>
    <p class="lead">Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>
    <a href="index.php" class="btn btn-outline-primary mt-3">Volver al inicio</a>
</div>

<script>
function mostrarAlerta() {
    Swal.fire({
        icon: 'error',
        title: 'Página no encontrada',
        text: 'La URL solicitada no existe o ha sido movida.',
        confirmButtonText: 'Entendido'
    });
}
</script>

</body>
</html>
