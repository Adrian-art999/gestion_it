<?php
session_start();
include '../includes/db.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesión vencida']);
    exit;
}
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$sql = "SELECT id, nombre, apellido, formacion, correo, telefono FROM empleados WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
echo json_encode($res->fetch_assoc());
?>