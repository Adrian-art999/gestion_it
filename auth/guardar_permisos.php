<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/security.php';
require_once '../includes/permisos.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión vencida']);
    exit;
}

if (!tienePermiso('super_admin')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'No tienes permiso para gestionar permisos del sistema']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
    exit;
}

// ── Re-autenticación: verificar contraseña del administrador activo ──
$adminPassword = $_POST['_admin_password'] ?? '';
if ($adminPassword === '') {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Debes ingresar tu contraseña para confirmar los cambios']);
    exit;
}

$stmtAdmin = $conn->prepare("SELECT password FROM usuarios WHERE id = ? LIMIT 1");
$stmtAdmin->bind_param('i', $_SESSION['user_id']);
$stmtAdmin->execute();
$adminRow = $stmtAdmin->get_result()->fetch_assoc();
$stmtAdmin->close();

if (!$adminRow || !password_verify($adminPassword, $adminRow['password'])) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Contraseña incorrecta. No se guardaron los cambios.']);
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'ID inválido']);
    exit;
}

// No permitir auto-modificación
if ($id === (int) $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'No puedes modificar tus propios permisos']);
    exit;
}

// Obtener usuario destino
$stmt = $conn->prepare("SELECT id, nombre_completo, rol FROM usuarios WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$target = $stmt->get_result()->fetch_assoc();

if (!$target) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'Usuario no encontrado']);
    exit;
}

// No modificar permisos del Superadmin por nombre (Gladys)
if (esNombreSuperAdmin($target['nombre_completo'] ?? '')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'No puedes modificar los permisos del Superadmin']);
    exit;
}

// Construir array de permisos desde POST
$todosPermisos = listaPermisos();
$nuevosPermisos = [];
foreach ($todosPermisos as $p) {
    $nuevosPermisos[$p] = !empty($_POST[$p]);
}

$jsonPermisos = json_encode($nuevosPermisos, JSON_UNESCAPED_UNICODE);

$stmtUp = $conn->prepare("UPDATE usuarios SET permisos = ? WHERE id = ?");
$stmtUp->bind_param('si', $jsonPermisos, $id);

if (!$stmtUp->execute()) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error al guardar permisos: ' . $stmtUp->error]);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Permisos actualizados correctamente']);
