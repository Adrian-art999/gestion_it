<?php
session_start();
include '../includes/db.php';
<<<<<<< HEAD
include '../includes/permisos.php';
require_once '../includes/functions.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

if (!isset($_SESSION['user_id'])) {
    echo "Sesión vencida";
    exit;
}

<<<<<<< HEAD
if (!tienePermiso('empleados_eliminar')) {
    http_response_code(403);
    echo "No tienes permiso para esta acción";
    exit;
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
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

$stmt = $conn->prepare("DELETE FROM empleados WHERE id = ? LIMIT 1");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
<<<<<<< HEAD
    registrar_log($conn, (int) $_SESSION['user_id'], "Eliminó al empleado ID {$id}");
    $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => 'Empleado eliminado correctamente'];
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    echo "success";
} else {
    echo "Error al eliminar empleado";
}
?>
