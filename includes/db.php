<?php
date_default_timezone_set('America/Caracas');

$conn = mysqli_connect("localhost", "root", "", "sistema_it");
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");

// Forzar MySQL a UTC−4 para que NOW() y CURRENT_TIMESTAMP coincidan con PHP
$conn->query("SET time_zone = '-04:00';");
?>