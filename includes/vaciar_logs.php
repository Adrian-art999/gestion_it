<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/permisos.php';
require_once __DIR__ . '/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión vencida']);
    exit;
}

if (!tienePermiso('bitacora')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'No tienes permiso para esta acción']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
    exit;
}

$conn->query("TRUNCATE TABLE logs_sistema");

echo json_encode(['ok' => true, 'message' => 'Historial vaciado correctamente']);
?>