<?php
session_start();
session_unset();     // Elimina todas las variables de sesión
session_destroy();   // Destruye la sesión

header("Location: index.php"); // O usa inicio.html si ese es tu archivo de inicio
exit;
?>
