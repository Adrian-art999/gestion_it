<?php
session_start();
include '../includes/db.php';
<<<<<<< HEAD
include '../includes/permisos.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Sesión vencida']);
    exit;
}
<<<<<<< HEAD

if (!tienePermiso('empleados_info')) {
    http_response_code(403);
    echo json_encode(['error' => 'No tienes permiso para ver información detallada']);
    exit;
}
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$sql = "SELECT id, nombre, apellido, formacion, correo, telefono FROM empleados WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
echo json_encode($res->fetch_assoc());
?>