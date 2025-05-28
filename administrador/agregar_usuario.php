<?php
session_start();
include '../db.php';
// Verifica si hay sesión iniciada y si el rol es 'admin'
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    acceso_denegado();
    exit();
}

function acceso_denegado() {
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Acceso Denegado</title>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
        <script>
            setTimeout(function() {
                window.history.back();
            }, 5000);
        </script>
        <style>
            body {
                margin: 0;
                padding: 0;
                background: linear-gradient(rgba(15, 23, 43, .9), rgba(15, 23, 43, .9)), url("../img/bg-hero.jpg");
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
                font-family: "Quicksand", sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #fff;
            }
            .container {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(6px);
                padding: 40px;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
                text-align: center;
                max-width: 400px;
            }
            .container h1 {
                color: #ffc107;
                margin-bottom: 20px;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #fea116;
                color: #fff;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 600;
                transition: background-color 0.3s ease;
                margin-top: 20px;
            }
            .btn:hover {
                background-color: #e6950b;
            }
            .info {
                margin-top: 15px;
                font-size: 0.9em;
                color: #eee;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Acceso Denegado</h1>
            <p>Solo los administradores pueden ver esta página.</p>
            <a class="btn" href="javascript:history.back()">Volver atrás</a>
            <p class="info">Serás redirigido automáticamente a la página anterior en 5 segundos.</p>
        </div>
    </body>
    </html>';
}


$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol']; // Nuevo campo para el rol

    if (!empty($usuario) && !empty($contrasena) && !empty($rol)) {
        $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario (nombre_usuario, contrasena, rol)
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $usuario, $hashed_password, $rol);

        if ($stmt->execute()) {
            $mensaje = "<div class='alert alert-success'>✅ Usuario '$usuario' agregado exitosamente como $rol.</div>";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/0b3f2bf674.js" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <!-- NAVBAR simple -->
    <nav class="navbar navbar-dark bg-dark py-3">
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
                    <i class="fas fa-user-plus me-2"></i>Agregar Usuario
                </h2>
                <p class="text-muted">Completa el formulario para registrar un nuevo usuario</p>
            </div>

            <?= $mensaje ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                </div>
                <div class="mb-4">
                    <label for="rol" class="form-label">Rol del Usuario</label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="">Selecciona un rol...</option>
                        <option value="admin">Administrador</option>
                        <option value="empleado">Empleado</option>
                    </select>
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
