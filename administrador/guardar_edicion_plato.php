<?php
include('../db.php');

$id = $_POST['id_item'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$imagen = $_POST['imagen'];
$id_categoria = $_POST['id_categoria'];

$sql = "UPDATE item_menu 
        SET nombre='$nombre', descripcion='$descripcion', precio=$precio, imagen='$imagen', id_categoria=$id_categoria 
        WHERE id_item = $id";

mysqli_query($conn, $sql);
header("Location: carta_restaurante.php");
?>
