<?php
session_start();
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'empleado'])) {
    header("Location: login.php");
    exit;
}

include('../db.php');

// Mensaje de confirmación para eliminación
if (isset($_GET['eliminado']) && $_GET['eliminado'] == '1') {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Testimonio eliminado!</strong> El testimonio ha sido eliminado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>';
}

// Determinar si se está mostrando aprobados o pendientes
$ver_aprobados = isset($_GET['aprobados']) && $_GET['aprobados'] == 1;

$titulo = $ver_aprobados ? "Testimonios Aprobados" : "Testimonios Pendientes";
$estado = $ver_aprobados ? 1 : 0;

$sql = "SELECT t.id, t.comentario, r.id_reserva, c.nombre, t.fecha_comentario 
        FROM testimonio t
        JOIN reserva r ON t.id_reserva = r.id_reserva
        JOIN cliente c ON r.id_cliente = c.id_cliente
        WHERE t.aprobado = ?
        ORDER BY t.fecha_comentario DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $estado);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $titulo ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 1.05rem; /* Tamaño de fuente aumentado */
        }
        
        .testimonio-container {
            background: white;
            padding: 18px;
            margin-bottom: 18px;
            border-radius: 8px;
            border-left: 4px solid #0d6efd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .testimonio-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .nombre-cliente {
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
        }
        
        .reserva-id {
            color: #6c757d;
            font-size: 0.95rem;
        }
        
        .fecha-comentario {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 12px;
        }
        
        .comentario-texto {
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 15px;
            padding: 10px 0;
        }
        
        .btn-action {
            padding: 8px 12px;
            font-size: 0.95rem;
            border-radius: 6px;
        }
        
        .btn-approve {
            background-color: #28a745;
            color: white;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-tab {
            padding: 10px 20px;
            font-size: 1rem;
            margin-right: 10px;
            border-radius: 6px;
        }
        
        .badge-approved {
            font-size: 0.95rem;
            padding: 6px 10px;
        }
        
        main {
            flex: 1;
            padding: 25px 0;
        }
        
        .page-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
        
        .tabs-container {
            margin-bottom: 25px;
        }
        
        .empty-message {
            font-size: 1.1rem;
            padding: 30px;
            text-align: center;
            color: #6c757d;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-dark py-3">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="../panel">
            <i class="fas fa-arrow-left me-2"></i>Volver al Panel
        </a>
    </div>
</nav>


<main class="container my-4">
    <div class="row">
        <div class="col-12">
            <!-- Mostrar mensaje de confirmación si existe -->
            <?php if (!empty($mensaje)) echo $mensaje; ?>
            <h1 class="page-title fw-bold">
                <i class="fas fa-comments me-2"></i><?= $titulo ?>
            <button onclick="window.location.reload();" class="btn btn-sm btn-link ms-2" title="Actualizar datos">
                <i class="fas fa-sync-alt"></i>
             </button>
            </h1>
        
            <div class="tabs-container">
                <a href="admin_testimonios.php" class="btn btn-tab <?= !$ver_aprobados ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="fas fa-clock me-1"></i> Pendientes
                </a>
                <a href="admin_testimonios.php?aprobados=1" class="btn btn-tab <?= $ver_aprobados ? 'btn-primary' : 'btn-outline-secondary' ?>">
                    <i class="fas fa-check-circle me-1"></i> Aprobados
                </a>
            </div>
            
            <?php
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    echo "<div class='testimonio-container'>";
                    echo "<div class='testimonio-header'>";
                    echo "<span class='nombre-cliente'>{$row['nombre']}</span>";
                    echo "<span class='reserva-id'>Reserva #{$row['id_reserva']}</span>";
                    echo "</div>";
                    echo "<div class='fecha-comentario'><i class='far fa-clock me-1'></i>{$row['fecha_comentario']}</div>";
                    echo "<div class='comentario-texto'>" . htmlspecialchars($row['comentario']) . "</div>";
                    
                    if (!$ver_aprobados) {
                        // Botones para testimonios pendientes
                        echo "<div class='d-flex gap-3'>";
                        echo "<form method='post' action='aprobar_testimonio.php'>";
                        echo "<input type='hidden' name='id' value='{$row['id']}'>";
                        echo "<button type='submit' name='accion' value='aprobar' class='btn btn-action btn-approve'>";
                        echo "<i class='fas fa-check me-1'></i> Aprobar";
                        echo "</button>";
                        echo "</form>";
                        
                        echo "<form method='post' action='aprobar_testimonio.php'>";
                        echo "<input type='hidden' name='id' value='{$row['id']}'>";
                        echo "<input type='hidden' name='ver_aprobados' value='0'>"; // 0 para pendientes
                        echo "<button type='submit' name='accion' value='eliminar' class='btn btn-action btn-delete' onclick=\"return confirm('¿Eliminar este testimonio?');\">";
                        echo "<i class='fas fa-trash-alt me-1'></i> Eliminar";
                        echo "</button>";
                        echo "</form>";
                        echo "</div>";
                    } else {
                        // Para testimonios aprobados
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        echo "<span class='badge bg-success badge-approved'><i class='fas fa-check-circle me-1'></i>Aprobado</span>";
                        
                        echo "<form method='post' action='aprobar_testimonio.php'>";
                        echo "<input type='hidden' name='id' value='{$row['id']}'>";
                        echo "<input type='hidden' name='ver_aprobados' value='1'>"; // 1 para aprobados
                        echo "<button type='submit' name='accion' value='eliminar' class='btn btn-action btn-delete' onclick=\"return confirm('¿Eliminar este testimonio aprobado?');\">";
                        echo "<i class='fas fa-trash-alt me-1'></i> Eliminar";
                        echo "</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    
                    echo "</div>";
                }
            } else {
                echo "<div class='empty-message'>";
                echo "<i class='far fa-comment-dots fa-2x mb-3'></i>";
                echo "<h4>No hay testimonios " . ($ver_aprobados ? "aprobados" : "pendientes") . "</h4>";
                echo "<p>Cuando haya nuevos testimonios, aparecerán aquí.</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</main>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3 mt-auto">
    © 2025 Cuisine X - Administración
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>