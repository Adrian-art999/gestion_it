<?php
session_start();
require_once '../includes/db.php';
<<<<<<< HEAD
require_once '../includes/permisos.php';
require_once '../includes/functions.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

header('Content-Type: application/json');

// ── Guardia de sesión ──────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión vencida. Inicia sesión nuevamente.']);
    exit;
}

<<<<<<< HEAD
if (!tienePermiso('usuarios_eliminar')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'No tienes permiso para esta acción']);
    exit;
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
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

<<<<<<< HEAD
// ── Obtener nombre del usuario antes de eliminar ──────────────────────────
$nombreEliminado = 'ID ' . $id;
$stmtNombre = $conn->prepare("SELECT nombre_completo FROM usuarios WHERE id = ? LIMIT 1");
if ($stmtNombre) {
    $stmtNombre->bind_param('i', $id);
    $stmtNombre->execute();
    $rowNombre = $stmtNombre->get_result()->fetch_assoc();
    if ($rowNombre) {
        $nombreEliminado = $rowNombre['nombre_completo'];
    }
    $stmtNombre->close();
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
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

<<<<<<< HEAD
registrar_log($conn, (int) $_SESSION['user_id'], "Eliminó al usuario ID {$id}");

$_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => 'Usuario eliminado correctamente'];
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
echo json_encode(['ok' => true, 'message' => 'Usuario eliminado correctamente']);
?>
