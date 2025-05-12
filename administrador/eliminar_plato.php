<?php
include('../db.php');
$id = $_GET['id'];

$query = "DELETE FROM item_menu WHERE id_item = $id";
mysqli_query($conn, $query);

header("Location: carta_restaurante.php?eliminado=1");
exit;
?>
