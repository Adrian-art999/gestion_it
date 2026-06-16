<?php
<<<<<<< HEAD
session_start();
include '../includes/db.php';
include '../includes/activity_history.php';
include '../includes/permisos.php';
require_once '../includes/functions.php';
=======
include '../includes/db.php';
include '../includes/activity_history.php';
session_start();
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

if (!isset($_SESSION['user_id'])) {
    echo "Sesión vencida";
    exit;
}

<<<<<<< HEAD
if (!tienePermiso('actividades_eliminar')) {
    http_response_code(403);
    echo "No tienes permiso para esta acción";
    exit;
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
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
<<<<<<< HEAD
    registrar_log($conn, (int) $_SESSION['user_id'], "Eliminó la actividad ID {$id}");
    $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => 'Actividad eliminada correctamente'];
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    echo "success";
} else {
    echo "error";
}
?>