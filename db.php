<?php
// Conexión a la base de datos
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "restaurante_x";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    http_response_code(500);
    die("Conexión fallida: " . $conn->connect_error);
}

// Configurar la codificación
$conn->set_charset("utf8mb4");
?>
