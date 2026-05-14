<?php
session_start();
include '../includes/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesión vencida']);
    exit;
}

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID no recibido']);
    exit;
}

$id = (int) $_GET['id'];
if ($id <= 0) {
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM actividades WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
if (!$stmt->execute()) {
    echo json_encode(['error' => 'No se pudo consultar actividad']);
    exit;
}
$res = $stmt->get_result();
echo json_encode($res->fetch_assoc() ?: []);
?>