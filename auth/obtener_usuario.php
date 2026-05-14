<?php
session_start();
require_once '../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión vencida']);
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'ID inválido']);
    exit;
}

$sql = "SELECT id, nombre_completo, username,
               COALESCE(NULLIF(formacion, ''), 'N/D') AS formacion,
               COALESCE(NULLIF(correo, ''), 'N/D') AS correo,
               COALESCE(NULLIF(telefono, ''), 'N/D') AS telefono
        FROM usuarios
        WHERE id = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error de conexión']);
    exit;
}

$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$u = $res->fetch_assoc();

if (!$u) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'Usuario no encontrado']);
    exit;
}

$nombreCompleto = trim((string) ($u['nombre_completo'] ?? ''));
$partes = preg_split('/\s+/', $nombreCompleto);
$nombre = $partes[0] ?? 'N/D';
$apellido = count($partes) > 1 ? implode(' ', array_slice($partes, 1)) : 'N/D';

echo json_encode([
    'ok' => true,
    'usuario' => [
        'id' => (int) $u['id'],
        'nombre' => $nombre ?: 'N/D',
        'apellido' => $apellido ?: 'N/D',
        'formacion' => $u['formacion'] ?: 'N/D',
        'username' => $u['username'] ?: 'N/D',
        'correo' => $u['correo'] ?: 'N/D',
        'telefono' => $u['telefono'] ?: 'N/D'
    ]
]);
?>
