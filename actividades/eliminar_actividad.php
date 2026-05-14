<?php
include '../includes/db.php';
include '../includes/activity_history.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Sesión vencida";
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

$stmt = $conn->prepare("DELETE FROM actividades WHERE id = ? LIMIT 1");
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
    echo "success";
} else {
    echo "error";
}
?>