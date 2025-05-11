<?php
include('../db.php'); // Asegúrate de que este archivo tiene tu conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = intval($_POST['id_reserva']);
    $id_mesa = intval($_POST['id_mesa']);

    // Actualizamos la reserva con la mesa seleccionada
    $sql = "UPDATE reserva SET id_mesa = $id_mesa WHERE id_reserva = $id_reserva";

    if (mysqli_query($conn, $sql)) {
        header("Location: gestionar_reservas.php?asignacion=exitosa");
        exit;
    } else {
        echo "Error al asignar mesa: " . mysqli_error($conn);
    }
} else {
    echo "Acceso no permitido.";
}
?>
