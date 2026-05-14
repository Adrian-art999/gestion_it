<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Sesión vencida";
    exit;
}

// Verificación de ID
if (!isset($_GET['id'])) {
    echo "ID no recibido";
    exit;
}

$id = (int) $_GET['id'];
if ($id <= 0) {
    echo "ID inválido";
    exit;
}

$stmt = $conn->prepare("DELETE FROM empleados WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error al eliminar empleado";
}
?>
