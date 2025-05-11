<?php 
session_start();

// Validar si el usuario ha iniciado sesión y tiene rol 'admin' o 'emple'
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'empleado'])) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Administrador</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 bg-light">

    <!-- NAVBAR RESPONSIVO -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <span class="small text-white">
                        <i class="fas fa-user me-2"></i><?= htmlspecialchars($_SESSION['usuario']) ?>
                    </span>
                <?php endif; ?>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin" aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarAdmin">
                <ul class="navbar-nav mb-2 mb-lg-0 d-flex align-items-center">
                    <?php if (
                    (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') ||
                    (isset($_SESSION['rol'], $_SESSION['usuario']) && $_SESSION['rol'] === 'empleado' && $_SESSION['usuario'] === 'empleado')
                        ): ?>
                        <li class="nav-item me-2">
                            <a href="administrador/gestionar_reservas.php" class="btn btn-outline-light">
                                <i class="fas fa-calendar-check me-1"></i>Gestionar Reservas
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (
                    (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') ||
                    (isset($_SESSION['rol'], $_SESSION['usuario']) && $_SESSION['rol'] === 'empleado' && $_SESSION['usuario'] === 'head_chef')
                        ): ?>
                        <li class="nav-item me-2">
                            <a href="administrador/carta_restaurante.php" class="btn btn-outline-light">
                                <i class="fas fa-utensils me-1"></i>Ver / Editar Carta
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                        <li class="nav-item me-2">
                            <a href="administrador/agregar_usuario.php" class="btn btn-outline-light">
                                <i class="fas fa-user-plus me-1"></i>Agregar Usuario
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a href="logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-1"></i>Salir
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container my-5 flex-grow-1 d-flex justify-content-center align-items-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-7 col-xl-6 bg-white p-5 rounded shadow-sm text-center">
            <div class="display-4 text-primary mb-3">
                <i class="fas fa-user-shield"></i>
            </div>
            <h2 class="fw-bold">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?> 👋</h2>
            <p class="text-muted mb-4">
                <?php
                echo $_SESSION['rol'] === 'admin' 
                    ? "Has ingresado correctamente al panel de administrador."
                    : ($_SESSION['usuario'] === 'head_chef'
                        ? "Has ingresado correctamente al panel de chef."
                        : "Has ingresado correctamente al panel de empleado.");
                ?>
            </p>
            <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                <?php if (
                (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') ||
                (isset($_SESSION['rol'], $_SESSION['usuario']) && $_SESSION['rol'] === 'empleado' && $_SESSION['usuario'] === 'empleado')
                ): ?>
                    <a href="administrador/gestionar_reservas.php" class="btn btn-primary btn-lg">
                        <i class="fas fa-calendar-check me-2"></i>Gestionar Reservas
                    </a>
                <?php endif; ?>

                <?php if (
                (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') ||
                (isset($_SESSION['rol'], $_SESSION['usuario']) && $_SESSION['rol'] === 'empleado' && $_SESSION['usuario'] === 'head_chef')
                ): ?>
                <a href="administrador/carta_restaurante.php" class="btn btn-dark btn-lg">
                    <i class="fas fa-utensils me-2"></i>Ver / Editar Carta
                </a>
                <?php endif; ?>

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-dark text-white text-center py-3 mt-auto">
        © 2025 Cuisine X - Panel de Administración
    </footer>

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>



