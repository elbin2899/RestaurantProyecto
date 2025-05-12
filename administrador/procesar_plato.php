<?php
include('../db.php');
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$imagen = $_POST['imagen'];
$id_categoria = $_POST['id_categoria'];

$sql = "INSERT INTO item_menu (nombre, descripcion, precio, imagen, id_categoria) 
        VALUES ('$nombre', '$descripcion', '$precio', '$imagen', $id_categoria)";
mysqli_query($conn, $sql);
header("Location: carta_restaurante.php?accion=exitosa&tipo=plato");
?>
