<?php
include('../db.php');
$id = $_GET['id'];

// Consultar estado actual
$query = "SELECT activo FROM item_menu WHERE id_item = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Cambiar el estado
$nuevo_estado = $row['activo'] ? 0 : 1;

$update = "UPDATE item_menu SET activo = $nuevo_estado WHERE id_item = $id";
mysqli_query($conn, $update);

// Redirigir con mensaje correspondiente
if ($nuevo_estado == 0) {
    header("Location: carta_restaurante.php?visibilidad=ocultado");
} else {
    header("Location: carta_restaurante.php?visibilidad=activado");
}
exit();
?>
