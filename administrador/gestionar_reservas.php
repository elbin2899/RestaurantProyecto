<?php
session_start();
include('../db.php'); // Se actualiza a db.php como archivo de conexión

// Verificar sesión y rol
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'empleado')) {
    echo "Acceso denegado.";
    exit;
}

// Lógica para eliminar (solo admin)
if (isset($_GET['eliminar']) && $_SESSION['rol'] === 'admin') {
    $id_reserva = intval($_GET['eliminar']);
    mysqli_query($conn, "DELETE FROM reserva WHERE id_reserva = $id_reserva");
    header('Location: gestionar_reservas.php');
    exit;
}

// Lógica para crear o actualizar una reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = isset($_POST['id_reserva']) ? intval($_POST['id_reserva']) : null;
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $personas = $_POST['personas'];
    $estado = $_POST['estado'];

    // Insertar cliente si no existe
    $query = "SELECT id_cliente FROM cliente WHERE email = '$email'";
    $resultado = mysqli_query($conn, $query);
    if (mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        $id_cliente = $fila['id_cliente'];
    } else {
        mysqli_query($conn, "INSERT INTO cliente (nombre, email, telefono) VALUES ('$nombre', '$email', '$telefono')");
        $id_cliente = mysqli_insert_id($conn);
    }

    // Validación para evitar duplicados por fecha, hora y número de personas
    $check_sql = "SELECT id_reserva FROM reserva WHERE fecha = '$fecha' AND hora = '$hora' AND numero_personas = $personas";
    if ($id_reserva) {
           $check_sql .= " AND id_reserva != $id_reserva";
    }
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Ya existe una reserva con la misma fecha, hora y número de personas.'); window.location.href = 'gestionar_reservas.php';</script>";
        exit;
    }

    if ($id_reserva) {
        // Al editar una reserva, también liberamos la mesa asignada
        $sql = "UPDATE reserva SET id_cliente=$id_cliente, fecha='$fecha', hora='$hora', numero_personas=$personas, estado='$estado', id_mesa=NULL WHERE id_reserva=$id_reserva";
    } else {
        $sql = "INSERT INTO reserva (id_cliente, fecha, hora, numero_personas, estado) VALUES ($id_cliente, '$fecha', '$hora', $personas, '$estado')";
    }
    mysqli_query($conn, $sql);
    header('Location: gestionar_reservas.php');
    exit;
}

// Obtener reservas
$reservas = mysqli_query($conn, "SELECT r.*, c.nombre AS cliente, c.email, c.telefono, r.id_mesa, (SELECT numero_mesa FROM mesa WHERE id_mesa = r.id_mesa) AS numero_mesa FROM reserva r JOIN cliente c ON r.id_cliente = c.id_cliente");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Reservas</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #f8f9fa; }
        h1 { font-weight: 700; color: #343a40; }
        .table th, .table td { vertical-align: middle; }
        .form-control, .form-select { border-radius: 0.4rem; }
        .btn i { margin-right: 5px; }
    </style>
   
</head>
<body class="container py-5">
    <div style="text-align: right; padding: 15px;">
    <a href="../administrador.php" style="text-decoration: none; color: #0F172B; font-weight: 600;" onmouseover="this.style.color='#FEA116'" onmouseout="this.style.color='#0F172B'">
        Página principal
    </a></div>
    <h1 class="mb-4 text-center"><i class="fas fa-utensils"></i> Panel de Gestión de Reservas</h1>
    
    <?php if (isset($_GET['asignacion']) && $_GET['asignacion'] === 'exitosa') : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Mesa asignada!</strong> La reserva ha sido actualizada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <form method="POST" class="mb-5 p-4 bg-white shadow rounded">
        <input type="hidden" name="id_reserva" id="id_reserva">
        <div class="row g-3">
            <div class="col-md-4">
            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del cliente" required>
            </div>
            <div class="col-md-4"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
            <div class="col-md-4">
            <input type="tel" name="telefono" class="form-control" placeholder="Teléfono"
                pattern="^(\+?\d{1,3})?\d{9}$"
                title="Debe contener 9 dígitos, opcionalmente con prefijo (+34, etc.)"
                maxlength="13">
            </div>
            <div class="col-md-4"><input type="date" name="fecha" class="form-control" id="fecha" required></div>
            <div class="col-md-4"><select name="hora" class="form-control" id="hora" required></select></div>
            <div class="col-md-2"><input type="number" name="personas" class="form-control" min="1" max="8" placeholder="# Personas" required></div>
            <div class="col-md-2">
                <select name="estado" class="form-select">
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmada">Confirmada</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div class="col-12"><button class="btn btn-success w-100"><i class="fas fa-save"></i> Guardar Reserva</button></div>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th><th>Cliente</th><th>Correo</th><th>Teléfono</th>
                    <th>Fecha</th><th>Hora</th><th>Personas</th><th>Estado</th><th>Mesa</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = mysqli_fetch_assoc($reservas)) : ?>
                    <tr>
                        <td><?= $r['id_reserva'] ?></td>
                        <td><?= $r['cliente'] ?></td>
                        <td><?= $r['email'] ?></td>
                        <td><?= $r['telefono'] ?></td>
                        <td><?= $r['fecha'] ?></td>
                        <td><?= $r['hora'] ?></td>
                        <td><?= $r['numero_personas'] ?></td>
                        <td><span class="badge bg-<?= $r['estado'] === 'pendiente' ? 'warning' : ($r['estado'] === 'confirmada' ? 'success' : 'danger') ?>"><?= ucfirst($r['estado']) ?></span></td>
                        <td><?= $r['numero_mesa'] ? $r['numero_mesa'] : '—' ?></td>
                        <td>
                            <a href="#" onclick="cargarDatosReserva('<?= $r['id_reserva'] ?>', '<?= $r['cliente'] ?>', '<?= $r['email'] ?>', '<?= $r['telefono'] ?>', '<?= $r['fecha'] ?>', '<?= $r['hora'] ?>', '<?= $r['numero_personas'] ?>', '<?= $r['estado'] ?>')" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="gestionar_reservas.php?eliminar=<?= $r['id_reserva'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta reserva?')">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                            <?php if (!$r['id_mesa']) : ?>
                                <form method="POST" action="asignar_mesa.php" class="mt-2">
                                    <input type="hidden" name="id_reserva" value="<?= $r['id_reserva'] ?>">
                                    <?php
                                    $fecha = $r['fecha'];
                                    $hora = $r['hora'];
                                    $personas = $r['numero_personas'];
                                    $consultaMesas = "SELECT * FROM mesa WHERE capacidad >= $personas AND capacidad <= $personas + 1 AND id_mesa NOT IN (
                                        SELECT id_mesa FROM reserva WHERE fecha = '$fecha' AND hora = '$hora' AND id_mesa IS NOT NULL
                                    )";
                                    $mesas = mysqli_query($conn, $consultaMesas);
                                    ?>
                                    <select name="id_mesa" class="form-select">
                                        <?php while ($m = mysqli_fetch_assoc($mesas)) : ?>
                                            <option value="<?= $m['id_mesa'] ?>">Mesa <?= $m['numero_mesa'] ?> (<?= $m['capacidad'] ?> personas - <?= $m['ubicacion'] ?>)</option>
                                        <?php endwhile; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-success mt-1">Asignar mesa</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<script src="function.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

