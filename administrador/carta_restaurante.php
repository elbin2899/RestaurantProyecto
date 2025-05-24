<?php 
session_start();
include('../db.php'); 
// Verifica si hay sesión iniciada
if (!isset($_SESSION['rol']) || !isset($_SESSION['usuario'])) {
    acceso_denegado();
    exit();
}

// Verifica si el rol es válido
if ($_SESSION['rol'] !== 'admin' && 
    !($_SESSION['rol'] === 'empleado' && $_SESSION['usuario'] === 'head_chef')) {
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
            <p>No tienes el permiso para ver esta página.</p>
            <a class="btn" href="javascript:history.back()">Volver atrás</a>
            <p class="info">Serás redirigido automáticamente a la página anterior en 5 segundos.</p>
        </div>
    </body>
    </html>';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de la Carta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
  
<body class="bg-light">
<?php if (isset($_GET['edicion']) && $_GET['edicion'] === 'exitosa'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>¡Plato actualizado!</strong> Los cambios se guardaron correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
<?php endif; ?>
<?php if (isset($_GET['accion']) && $_GET['accion'] === 'exitosa'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>¡<?= $_GET['tipo'] === 'categoria' ? 'Categoría' : 'Plato' ?> guardado!</strong> La operación se completó correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
    </div>
<?php endif; ?>
<?php if (isset($_GET['eliminado'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        Plato eliminado correctamente
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (isset($_GET['visibilidad'])): ?>
    <?php if ($_GET['visibilidad'] === 'ocultado'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>¡Plato ocultado!</strong> El plato ya no está visible en la carta.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php elseif ($_GET['visibilidad'] === 'activado'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Plato activado!</strong> El plato vuelve a estar visible en la carta.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="container mt-5">
    <div style="text-align: right; padding: 15px;">
    <a href="../panel" style="text-decoration: none; color: #0F172B; font-weight: 600;" onmouseover="this.style.color='#FEA116'" onmouseout="this.style.color='#0F172B'">
        Página principal
    </a>
    </div>
    <h1 class="mb-4">📋 Gestión de la Carta del Restaurante</h1>

    <!-- Formulario para añadir categoría -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">➕ Nueva Categoría</div>
        <div class="card-body">
            <form action="procesar_categoria.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre:</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Guardar Categoría</button>
            </form>
        </div>
    </div>

    <!-- Formulario para añadir plato -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">➕ Nuevo Plato</div>
        <div class="card-body">
            <form action="procesar_plato.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Nombre del Plato:</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripción:</label>
                    <textarea name="descripcion" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Precio (€):</label>
                    <input type="number" name="precio" step="0.01" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de la imagen</label>
                    <input type="text" name="imagen" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoría:</label>
                    <select name="id_categoria" class="form-select" required>
                        <option value="">-- Selecciona una categoría --</option>
                        <?php
                        $cat_query = "SELECT * FROM categoria_menu";
                        $cat_result = mysqli_query($conn, $cat_query);
                        while ($cat = mysqli_fetch_assoc($cat_result)) {
                            echo "<option value='{$cat['id_categoria']}'>{$cat['nombre']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Guardar Plato</button>
            </form>
        </div>
    </div>

    <!-- Tabla de platos existentes -->
    <div class="card">
        <div class="card-header bg-dark text-white">🍽️ Platos Registrados</div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Imagen</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                    
                    <?php
                    $query = "SELECT i.*, c.nombre AS categoria 
                            FROM item_menu i 
                            LEFT JOIN categoria_menu c ON i.id_categoria = c.id_categoria
                            ORDER BY 
                                CASE 
                                WHEN c.nombre = 'Entrante' THEN 1
                                WHEN c.nombre = 'Plato Principal' THEN 2
                                WHEN c.nombre = 'Postre' THEN 3
                                ELSE 4
                                END,
                                i.id_item DESC";  /* Orden descendente para mostrar primero los más recientes */
                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $estado = $row['activo'] ? "✅ Sí" : "❌ No";
                        echo "<tr>
                                <td>{$row['nombre']}</td>
                                <td>{$row['descripcion']}</td>
                                <td>€{$row['precio']}</td>
                                <td>{$row['categoria']}</td>
                                <td><img src='img/{$row['imagen']}' width='80'></td>
                                <td>
                                    <div class='btn-group d-flex flex-column align-items-center gap-1'>
                                        {$estado}
                                        <a href='ocultar_plato.php?id={$row['id_item']}' class='btn btn-sm btn-warning' title='" . ($row['activo'] ? 'Ocultar' : 'Activar') . "'>
                                            <i class='fas " . ($row['activo'] ? 'fa-eye-slash' : 'fa-eye') . "'></i>
                                        </a>
                                    </div>
                                </td>

                                <td>
                                    <div class='btn-group d-flex flex-column align-items-center gap-1'>
                                        <a href='editar_plato.php?id={$row['id_item']}' class='btn btn-sm btn-info' title='Editar'>
                                            <i class='fas fa-edit'></i>
                                        </a>
                                        <a href='eliminar_plato.php?id={$row['id_item']}' class='btn btn-sm btn-danger' title='Eliminar' onclick=\"return confirm('¿Seguro que quieres eliminar este plato?')\">
                                            <i class='fas fa-trash-alt'></i>
                                        </a>
                                    </div>
                                </td>
                             </tr>";
                    }
                    ?>
                </tbody><!--carga una imagen desde la carpeta img/ usando el nombre del archivo guardado en la base de datos.-->
            </table>
        </div>
    </div>

</div>
<!-- Añadido para los alerts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
