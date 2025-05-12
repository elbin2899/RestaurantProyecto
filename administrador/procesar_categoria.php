<?php
include('../db.php');
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

$sql = "INSERT INTO categoria_menu (nombre, descripcion) VALUES ('$nombre', '$descripcion')";
mysqli_query($conn, $sql);
header("Location: carta_restaurante.php?accion=exitosa&tipo=categoria");
?>
