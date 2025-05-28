<?php
session_start();

include('../db.php');

if (!isset($_SESSION['usuario']) || !in_array($_SESSION['rol'], ['admin', 'empleado'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_POST['id']);
$accion = $_POST['accion'];

// Verificar si estamos viendo aprobados o pendientes (para mantener el filtro)
$ver_aprobados = isset($_POST['ver_aprobados']) ? intval($_POST['ver_aprobados']) : 0;

if ($accion == 'aprobar') {
    $stmt = $conn->prepare("UPDATE testimonio SET aprobado = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Redireccionar sin parámetro de eliminación
    header("Location: admin_testimonios.php" . ($ver_aprobados ? "?aprobados=1" : ""));
} elseif ($accion == 'eliminar') {
    $stmt = $conn->prepare("DELETE FROM testimonio WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Redireccionar con parámetro de eliminación y manteniendo el filtro
    header("Location: admin_testimonios.php" . ($ver_aprobados ? "?aprobados=1&eliminado=1" : "?eliminado=1"));
}
exit;
?>
