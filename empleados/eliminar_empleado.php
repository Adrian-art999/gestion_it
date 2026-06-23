<?php
session_start();
include '../includes/db.php';
include '../includes/permisos.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    echo "Sesión vencida";
    exit;
}

if (!tienePermiso('empleados_eliminar')) {
    http_response_code(403);
    echo "No tienes permiso para esta acción";
    exit;
}


// Verificación de ID
if (!isset($_GET['id'])) {
    echo "ID no recibido";
    exit;
}

$id = (int) $_GET['id'];
if ($id <= 0) {
    echo "ID inválido";
    exit;
}

// ── Obtener datos del empleado ANTES de borrar ──
$stmtDatos = $conn->prepare("SELECT nombre, apellido, formacion, correo, telefono FROM empleados WHERE id = ? LIMIT 1");
$stmtDatos->bind_param('i', $id);
$stmtDatos->execute();
$empleadoDatos = $stmtDatos->get_result()->fetch_assoc();
$stmtDatos->close();

$stmt = $conn->prepare("DELETE FROM empleados WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    $detalleLog = json_encode([
        'tipo' => 'empleado',
        'accion' => 'eliminacion',
        'empleado_id' => $id,
        'nombre' => $empleadoDatos['nombre'] ?? '',
        'apellido' => $empleadoDatos['apellido'] ?? '',
        'formacion' => $empleadoDatos['formacion'] ?? '',
        'correo' => $empleadoDatos['correo'] ?? '',
        'telefono' => $empleadoDatos['telefono'] ?? ''
    ], JSON_UNESCAPED_UNICODE);
    registrar_log($conn, (int) $_SESSION['user_id'], "Eliminó al empleado ID {$id}... (Ver info)", $detalleLog);
    $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => 'Empleado eliminado correctamente'];
    echo "success";
} else {
    echo "Error al eliminar empleado";
}
?>
