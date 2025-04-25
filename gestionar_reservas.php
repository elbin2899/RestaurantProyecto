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
        // Actualizar
        $sql = "UPDATE reserva SET id_cliente=$id_cliente, fecha='$fecha', hora='$hora', numero_personas=$personas, estado='$estado' WHERE id_reserva=$id_reserva";
    } else {
        // Crear nueva
        $sql = "INSERT INTO reserva (id_cliente, fecha, hora, numero_personas, estado) VALUES ($id_cliente, '$fecha', '$hora', $personas, '$estado')";
    }
    mysqli_query($conn, $sql);
    header('Location: gestionar_reservas.php');
    exit;
}

// Obtener reservas
$reservas = mysqli_query($conn, "SELECT r.*, c.nombre AS cliente, c.email, c.telefono FROM reserva r
    JOIN cliente c ON r.id_cliente = c.id_cliente");
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
    <h1 class="mb-4 text-center"><i class="fas fa-utensils"></i> Panel de Gestión de Reservas</h1>

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
                    <th>Fecha</th><th>Hora</th><th>Personas</th><th>Estado</th><th>Acciones</th>
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
                        <td>
                            <a href="#" onclick="cargarDatosReserva('<?= $r['id_reserva'] ?>', '<?= $r['cliente'] ?>', '<?= $r['email'] ?>', '<?= $r['telefono'] ?>', '<?= $r['fecha'] ?>', '<?= $r['hora'] ?>', '<?= $r['numero_personas'] ?>', '<?= $r['estado'] ?>')" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="gestionar_reservas.php?eliminar=<?= $r['id_reserva'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta reserva?')"
                               <?= $_SESSION['rol'] !== 'admin' ? 'style=\"pointer-events: none; opacity: 0.5;\"' : '' ?>>
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function cargarDatosReserva(id, nombre, email, telefono, fecha, hora, personas, estado) {
            document.getElementById('id_reserva').value = id;
            document.getElementsByName('nombre')[0].value = nombre;
            document.getElementsByName('email')[0].value = email;
            document.getElementsByName('telefono')[0].value = telefono;
            document.getElementsByName('fecha')[0].value = fecha;
            document.getElementsByName('hora')[0].value = hora;
            document.getElementsByName('personas')[0].value = personas;
            document.getElementsByName('estado')[0].value = estado;
        }
    </script><!--Ese script es el encargado de rellenar automáticamente el formulario cuando haces clic en el botón “Editar”-->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fechaInput = document.getElementById('fecha');
            const hoy = new Date().toISOString().split('T')[0];
            fechaInput.min = hoy;
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const horaSelect = document.getElementById('hora');

            const crearOpciones = (inicio, fin, label) => {
                const grupo = document.createElement('optgroup');
                grupo.label = label;

                for (let h = inicio; h <= fin; h++) {
                    const minutos = (h === fin) ? [0] : [0, 30];
                    minutos.forEach(m => {
                        const hora = `${h.toString().padStart(2, '0')}:${m === 0 ? '00' : '30'}`;
                        const opcion = document.createElement('option');
                        opcion.value = hora;
                        opcion.textContent = hora;
                        grupo.appendChild(opcion);
                    });
                }
                return grupo;
            };

            horaSelect.appendChild(crearOpciones(12, 16, 'Almuerzo'));
            horaSelect.appendChild(crearOpciones(19, 23, 'Cena'));
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const nombre = document.getElementById('nombre');

            form.addEventListener('submit', function (e) {
                // Validar nombre
                const nombreVal = nombre.value.trim();
                const nombreRegex = /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{1,30}$/;

                if (!nombreRegex.test(nombreVal)) {
                    alert('El nombre solo puede contener letras y espacios (máx. 30 caracteres).');
                    nombre.focus();
                    e.preventDefault();
                    return;
                }

            });
        });
    </script>

</body>
</html>

