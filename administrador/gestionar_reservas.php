<?php
session_start();
include('../db.php'); // Se actualiza a db.php como archivo de conexión

// Verificar sesión y rol
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] !== 'empleado')) {
    echo "Acceso denegado.";
    exit;
}

// Lógica para eliminar (solo admin y empleado)
if (isset($_GET['eliminar']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'empleado')) {
    $id_reserva = intval($_GET['eliminar']);
    mysqli_query($conn, "DELETE FROM reserva WHERE id_reserva = $id_reserva");
    header('Location: gestionar_reservas.php?eliminacion=exitosa');
    exit;
}

// 🔁 Cambio rápido de estado desde el dropdown en la tabla
if (isset($_POST['cambiar_estado'])) {
    $id = intval($_POST['id_reserva']);
    $nuevoEstado = $_POST['estado'];

    mysqli_query($conn, "UPDATE reserva SET estado = '$nuevoEstado' WHERE id_reserva = $id");

    header('Location: gestionar_reservas.php?modificacion=exitosa');
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

    // Al crear es "confirmada", pero si viene por edición permitimos estado personalizado
    $estado = isset($_POST['estado']) ? $_POST['estado'] : "confirmada";

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

    // Buscar la mesa más pequeña posible que pueda acomodar a esa cantidad de personas
$consulta_capacidad = "
SELECT capacidad FROM mesa 
WHERE capacidad >= ? 
ORDER BY capacidad ASC 
LIMIT 1
";
$stmt1 = $conn->prepare($consulta_capacidad);
$stmt1->bind_param("i", $personas);
$stmt1->execute();
$res1 = $stmt1->get_result();

if ($res1->num_rows === 0) {
echo "<script>alert('No hay mesas adecuadas disponibles.'); window.location.href = 'gestionar_reservas.php';</script>";
exit;
}

$capacidad_minima = $res1->fetch_assoc()['capacidad'];

// 1. Total de mesas con esa capacidad
$sql_total_mesas = "SELECT COUNT(*) as total FROM mesa WHERE capacidad = ?";
$stmt2 = $conn->prepare($sql_total_mesas);
$stmt2->bind_param("i", $capacidad_minima);
$stmt2->execute();
$res2 = $stmt2->get_result();
$total_mesas = $res2->fetch_assoc()['total'];

// 2. Cuántas de esas ya están ocupadas en esa fecha y hora
$sql_reservadas = "
SELECT COUNT(*) as total FROM reserva r
INNER JOIN mesa m ON r.id_mesa = m.id_mesa
WHERE m.capacidad = ? AND r.fecha = ? AND r.hora = ? AND r.id_mesa IS NOT NULL
";
$stmt3 = $conn->prepare($sql_reservadas);
$stmt3->bind_param("iss", $capacidad_minima, $fecha, $hora);
$stmt3->execute();
$res3 = $stmt3->get_result();
$mesas_ocupadas = $res3->fetch_assoc()['total'];

// Comparar
if ($mesas_ocupadas >= $total_mesas) {
echo "<script>alert('No hay mesas disponibles para $personas personas en esa fecha y hora.'); window.location.href = 'gestionar_reservas.php';</script>";
exit;
}


    if ($id_reserva) {
        // Al editar una reserva, también liberamos la mesa asignada
        $sql = "UPDATE reserva SET id_cliente=$id_cliente, fecha='$fecha', hora='$hora', numero_personas=$personas, estado='$estado', id_mesa=NULL WHERE id_reserva=$id_reserva";
    } else {
        $sql = "INSERT INTO reserva (id_cliente, fecha, hora, numero_personas, estado) VALUES ($id_cliente, '$fecha', '$hora', $personas, '$estado')";
    }
    mysqli_query($conn, $sql);
    if ($id_reserva) {
        header('Location: gestionar_reservas.php?edicion=exitosa');
    } else {
        header('Location: gestionar_reservas.php?creacion=exitosa');
    }
    exit;
}

// Obtener reservas
$reservas = mysqli_query($conn, "
    SELECT 
        r.*, 
        c.nombre AS cliente, 
        c.email, 
        c.telefono, 
        r.id_mesa, 
        (SELECT numero_mesa FROM mesa WHERE id_mesa = r.id_mesa) AS numero_mesa 
    FROM reserva r 
    JOIN cliente c ON r.id_cliente = c.id_cliente
    ORDER BY r.fecha_creacion DESC
");
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
    <a href="../panel" style="text-decoration: none; color: #0F172B; font-weight: 600;" onmouseover="this.style.color='#FEA116'" onmouseout="this.style.color='#0F172B'">
        Página principal
    </a></div>
    <h1 class="mb-4 text-center"><i class="fas fa-utensils"></i> Panel de Gestión de Reservas</h1>
    
    <?php if (isset($_GET['asignacion']) && $_GET['asignacion'] === 'exitosa') : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Mesa asignada!</strong> La reserva ha sido actualizada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['modificacion']) && $_GET['modificacion'] === 'exitosa') : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Reserva modificada!</strong> El estado ha sido actualizado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['creacion']) && $_GET['creacion'] === 'exitosa') : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Reserva guardada!</strong> La reserva ha sido registrada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['edicion']) && $_GET['edicion'] === 'exitosa') : ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <strong>¡Reserva actualizada!</strong> Los datos han sido modificados correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['eliminacion']) && $_GET['eliminacion'] === 'exitosa') : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>¡Reserva eliminada!</strong> La reserva ha sido borrada correctamente.
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
                        <td>
                            <form method="POST" action="gestionar_reservas.php" class="d-flex align-items-center">
                                <input type="hidden" name="cambiar_estado" value="1">
                                <input type="hidden" name="id_reserva" value="<?= $r['id_reserva'] ?>">
                                <select name="estado" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                    <option value="confirmada" <?= $r['estado'] === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                    <option value="cancelada" <?= $r['estado'] === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                </select>
                            </form>
                        </td>

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

