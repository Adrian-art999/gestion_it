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
    echo json_encode(['ok' => false, 'message' => 'No tienes permiso para ver los permisos del sistema']);
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'ID inválido']);
    exit;
}

$stmt = $conn->prepare("SELECT id, nombre_completo, username, rol, permisos FROM usuarios WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'Usuario no encontrado']);
    exit;
}

// Si es Superadmin, retornar permisos completos
$esSuper = esNombreSuperAdmin($user['nombre_completo'] ?? '');
$permisos = permisosDefault($user['rol'] ?? 'tecnico');

if (!empty($user['permisos'])) {
    $decoded = json_decode($user['permisos'], true);
    if (is_array($decoded)) {
        // Migración: mapear roles_gestionar antiguo → super_admin
        if (isset($decoded['roles_gestionar'])) {
            $decoded['super_admin'] = $decoded['roles_gestionar'];
            unset($decoded['roles_gestionar']);
        }
        $permisos = array_merge($permisos, $decoded);
    }
}

echo json_encode([
    'ok'      => true,
    'usuario' => [
        'id'       => (int) $user['id'],
        'nombre'   => $user['nombre_completo'],
        'username' => $user['username'],
        'rol'      => $user['rol'],
    ],
    'es_superadmin' => $esSuper,
    'permisos'      => $permisos,
]);
