<?php
include('../db.php');

// 1. Verifica si se pasó un ID por GET
if (!isset($_GET['id'])) {
    echo "ID no proporcionado.";
    exit;
}

$id = $_GET['id'];

// 2. Obtener datos del plato con JOIN para categoría
$query = "SELECT * FROM item_menu WHERE id_item = $id";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) !== 1) {
    echo "Plato no encontrado.";
    exit;
}

$plato = mysqli_fetch_assoc($result);

// 3. Obtener todas las categorías
$query_cat = "SELECT * FROM categoria_menu";
$result_cat = mysqli_query($conn, $query_cat);

// 4. Procesar el formulario de actualización
if (isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $id_categoria = $_POST['id_categoria'];

    if (!empty($_FILES['imagen']['name'])) {
        $imagen = $_FILES['imagen']['name'];
        $ruta_temp = $_FILES['imagen']['tmp_name'];
        move_uploaded_file($ruta_temp, "img/$imagen");

        $query_update = "UPDATE item_menu SET 
            nombre='$nombre',
            descripcion='$descripcion',
            precio='$precio',
            id_categoria='$id_categoria',
            imagen='$imagen'
            WHERE id_item=$id";
    } else {
        $query_update = "UPDATE item_menu SET 
            nombre='$nombre',
            descripcion='$descripcion',
            precio='$precio',
            id_categoria='$id_categoria'
            WHERE id_item=$id";
    }

    if (mysqli_query($conn, $query_update)) {
        header("Location: carta_restaurante.php?edicion=exitosa");
        exit;
    } else {
        $error = mysqli_error($conn);
    }
}
?>

<!-- HTML DEL FORMULARIO -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Plato</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Error!</strong> <?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    
    <h2 class="mb-4">Editar Plato</h2>
    <form action="" method="POST" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= $plato['nombre'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required><?= $plato['descripcion'] ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio (€)</label>
            <input type="number" name="precio" class="form-control" step="0.01" value="<?= $plato['precio'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <input type="number" name="id_categoria" class="form-control" value="<?= $plato['id_categoria'] ?>" required>
                <?php while ($cat = mysqli_fetch_assoc($result_cat)): ?>
                    <option value="<?= $cat['id_categoria'] ?>"
                        <?= $cat['id_categoria'] == $plato['id_categoria'] ? 'selected' : '' ?>>
                        <?= $cat['nombre'] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen actual</label><br>
            <img src="img/<?= $plato['imagen'] ?>" width="120" class="img-thumbnail mb-2"><br>
            <input type="file" name="imagen" class="form-control">
            <small class="text-muted">Puedes subir una nueva imagen si deseas cambiarla.</small>
        </div>

        <button type="submit" name="actualizar" class="btn btn-primary">Guardar Cambios</button>
        <a href="carta_restaurante.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

