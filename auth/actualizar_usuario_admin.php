<?php
session_start();
require_once '../includes/db.php';
<<<<<<< HEAD
require_once '../includes/permisos.php';
require_once '../includes/functions.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión vencida']);
    exit;
}

<<<<<<< HEAD
if (!tienePermiso('usuarios_editar')) {
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
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$username = trim($_POST['username'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$formacion = trim($_POST['formacion'] ?? '');

if ($id <= 0 || $nombre === '' || $apellido === '' || $username === '') {
    echo json_encode(['ok' => false, 'message' => 'Datos inválidos']);
    exit;
}

<<<<<<< HEAD
// Validar que nombre y apellido no contengan números
if (preg_match('/\d/', $nombre) || preg_match('/\d/', $apellido)) {
    echo json_encode(['ok' => false, 'message' => 'El nombre y apellido no pueden contener números']);
    exit;
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
$sqlDupUser = "SELECT id FROM usuarios WHERE username = ? AND id <> ? LIMIT 1";
$stmtDupUser = $conn->prepare($sqlDupUser);
$stmtDupUser->bind_param('si', $username, $id);
$stmtDupUser->execute();
if ($stmtDupUser->get_result()->fetch_assoc()) {
    echo json_encode(['ok' => false, 'message' => 'Username ya existe en otro usuario']);
    exit;
}
if ($correo !== '') {
    $sqlDupCorreo = "SELECT id FROM usuarios WHERE correo = ? AND id <> ? LIMIT 1";
    $stmtDupCorreo = $conn->prepare($sqlDupCorreo);
    $stmtDupCorreo->bind_param('si', $correo, $id);
    $stmtDupCorreo->execute();
    if ($stmtDupCorreo->get_result()->fetch_assoc()) {
        echo json_encode(['ok' => false, 'message' => 'Correo ya existe en otro usuario']);
        exit;
    }
}

$nombreCompleto = trim($nombre . ' ' . $apellido);
$formacionNorm = $formacion !== '' ? $formacion : 'N/D';
$correoNorm = $correo !== '' ? mb_strtolower($correo, 'UTF-8') : 'N/D';
$telefonoNorm = $telefono !== '' ? $telefono : 'N/D';

$sql = "UPDATE usuarios
        SET nombre_completo = ?, formacion = ?, username = ?, correo = ?, telefono = ?
        WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssi', $nombreCompleto, $formacionNorm, $username, $correoNorm, $telefonoNorm, $id);

if (!$stmt->execute()) {
    echo json_encode(['ok' => false, 'message' => 'No se pudo actualizar el usuario']);
    exit;
}

<<<<<<< HEAD
registrar_log($conn, (int) $_SESSION['user_id'], "Actualizó al usuario ID {$id}");
$_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Usuario guardado!'];
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
echo json_encode(['ok' => true, 'message' => 'Usuario actualizado correctamente']);
?>
