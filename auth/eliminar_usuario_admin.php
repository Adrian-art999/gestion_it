<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

// ── Guardia de sesión ──────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión vencida. Inicia sesión nuevamente.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'ID de usuario inválido']);
    exit;
}

// ── Prevenir auto-eliminación ──────────────────────────────────────────────
if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'No puedes eliminar tu propio usuario']);
    exit;
}

// ── Ejecutar eliminación ───────────────────────────────────────────────────
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ? LIMIT 1");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error interno al preparar la consulta']);
    exit;
}
$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo eliminar el usuario']);
    exit;
}

if ($stmt->affected_rows === 0) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'El usuario no existe o ya fue eliminado']);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Usuario eliminado correctamente']);
?>
