<?php
include '../db.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    if (!empty($usuario) && !empty($contrasena)) {
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nombre_usuario, contrasena, rol)
                VALUES (?, ?, 'empleado')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario, $hashed_password);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>✅ Usuario '$usuario' agregado exitosamente.</div>";
        } else {
            $mensaje = "<div class='alert alert-danger'>❌ Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    } else {
        $mensaje = "<div class='alert alert-warning'>❗ Completa todos los campos.</div>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/0b3f2bf674.js" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <!-- NAVBAR simple -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../panel">
                <i class="fas fa-arrow-left me-2"></i>Volver al Panel
            </a>
        </div>
    </nav>

    <!-- FORMULARIO CENTRADO -->
    <main class="container my-5 flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 bg-white p-5 rounded shadow-sm">
            <div class="mb-4 text-center">
                <h2 class="fw-bold text-success">
                    <i class="fas fa-user-plus me-2"></i>Agregar Usuario (Empleado)
                </h2>
                <p class="text-muted">Completa el formulario para registrar un nuevo usuario</p>
            </div>

            <?= $mensaje ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                </div>
                <div class="mb-4">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">
                    <i class="fas fa-plus-circle me-2"></i>Crear Usuario
                </button>
            </form>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        © 2025 Cuisine X - Administración
    </footer>
</body>
</html>
