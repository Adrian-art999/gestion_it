<?php
declare(strict_types=1);

session_start();
require_once '../includes/db.php';
require_once '../includes/activity_history.php';
<<<<<<< HEAD
require_once '../includes/functions.php';
require_once '../includes/permisos.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

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

<<<<<<< HEAD
if (!tienePermiso('actividades_finalizar')) {
    http_response_code(403);
    echo "No tienes permiso para esta acción";
    exit;
}

asegurarColumnaFechaFin($conn);

$ahora_fin = date('Y-m-d H:i:s');
$sql = "UPDATE actividades
        SET estado = 'Finalizada',
            fecha_fin = ?
=======
$sql = "UPDATE actividades
        SET estado = 'Finalizada'
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
        WHERE id = ? AND estado = 'En progreso'
        LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo "Error SQL: " . $conn->error;
    exit;
}
<<<<<<< HEAD
$stmt->bind_param('si', $ahora_fin, $id);
=======
$stmt->bind_param('i', $id);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
if (!$stmt->execute()) {
    http_response_code(500);
    echo "Error SQL: " . $stmt->error;
    exit;
}

if ($stmt->affected_rows <= 0) {
    echo "no_changes";
    exit;
}

<<<<<<< HEAD
$_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Actividad finalizada correctamente!'];

$usuarioNombre = (string) ($_SESSION['nombre'] ?? 'Sistema');
$usuarioId = (int) ($_SESSION['user_id'] ?? 0);
registrar_log($conn, $usuarioId, "Finalizó la actividad ID {$id}");
=======
$usuarioNombre = (string) ($_SESSION['nombre'] ?? 'Sistema');
$usuarioId = (int) ($_SESSION['user_id'] ?? 0);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
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
