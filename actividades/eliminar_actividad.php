<?php
session_start();
include '../includes/db.php';
include '../includes/activity_history.php';
include '../includes/permisos.php';
require_once '../includes/functions.php';
require_once '../includes/db_schema.php';

if (!isset($_SESSION['user_id'])) {
    echo "Sesión vencida";
    exit;
}

if (!tienePermiso('actividades_eliminar')) {
    http_response_code(403);
    echo "No tienes permiso para esta acción";
    exit;
}

if (!isset($_POST['id'])) {
    echo "ID no recibido";
    exit;
}

$id = (int) $_POST['id'];
if ($id <= 0) {
    echo "ID inválido";
    exit;
}

// Soft Delete: marcar como no visible en lugar de borrar
asegurarColumnaVisibleActividades($conn);

// Obtener datos de la actividad antes de ocultarla (para el detalle del log)
$stmtDatos = $conn->prepare("SELECT descripcion, area, estado, responsables_data FROM actividades WHERE id = ? LIMIT 1");
$stmtDatos->bind_param('i', $id);
$stmtDatos->execute();
$datosActividad = $stmtDatos->get_result()->fetch_assoc();
$stmtDatos->close();

$descripcionAct = $datosActividad['descripcion'] ?? 'N/D';
$areaAct = $datosActividad['area'] ?? 'N/D';
$responsablesAct = $datosActividad['responsables_data'] ?? '[]';

$stmt = $conn->prepare("UPDATE actividades SET visible = 0 WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    $usuarioNombre = (string) ($_SESSION['nombre'] ?? 'Sistema');
    $usuarioId = (int) ($_SESSION['user_id'] ?? 0);
    registrarHistorialActividad(
        $conn,
        $id,
        'ELIMINACION',
        "El usuario {$usuarioNombre} eliminó la actividad",
        $usuarioId,
        $usuarioNombre
    );
    $detalleLog = json_encode([
        'tipo' => 'actividad',
        'accion' => 'eliminacion',
        'actividad_id' => $id,
        'descripcion' => mb_substr($descripcionAct, 0, 150),
        'area' => $areaAct,
        'responsables' => $responsablesAct
    ], JSON_UNESCAPED_UNICODE);
    registrar_log($conn, (int) $_SESSION['user_id'], "Eliminó la actividad ID {$id}... (Ver info)", $detalleLog);
    $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => 'Actividad eliminada correctamente'];
    echo "success";
} else {
    echo "error";
}
?>