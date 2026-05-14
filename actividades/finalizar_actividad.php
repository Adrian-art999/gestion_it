<?php
declare(strict_types=1);

session_start();
require_once '../includes/db.php';
require_once '../includes/activity_history.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Sesión vencida";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Método no permitido";
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    echo "ID inválido";
    exit;
}

$sql = "UPDATE actividades
        SET estado = 'Finalizada'
        WHERE id = ? AND estado = 'En progreso'
        LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo "Error SQL: " . $conn->error;
    exit;
}
$stmt->bind_param('i', $id);
if (!$stmt->execute()) {
    http_response_code(500);
    echo "Error SQL: " . $stmt->error;
    exit;
}

if ($stmt->affected_rows <= 0) {
    echo "no_changes";
    exit;
}

$usuarioNombre = (string) ($_SESSION['nombre'] ?? 'Sistema');
$usuarioId = (int) ($_SESSION['user_id'] ?? 0);
registrarHistorialActividad(
    $conn,
    $id,
    'FINALIZACION',
    "El usuario {$usuarioNombre} finalizó la actividad",
    $usuarioId,
    $usuarioNombre
);

echo "success";
?>
