<?php include('../db.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de la Carta</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
  
<body class="bg-light">

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
                              LEFT JOIN categoria_menu c ON i.id_categoria = c.id_categoria";
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
</body>
</html>
