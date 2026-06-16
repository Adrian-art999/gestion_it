<?php
session_start();
include '../includes/db.php';
include '../includes/db_schema.php';
<<<<<<< HEAD
include '../includes/permisos.php';
require_once '../includes/functions.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

if (!isset($_SESSION['user_id'])) {
    echo 'Error: Sesión vencida';
    exit;
}

asegurarColumnasEmpleados($conn);

$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$formacion = trim($_POST['formacion'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$id = isset($_POST['id_empleado']) ? (int) $_POST['id_empleado'] : 0;

<<<<<<< HEAD
if ($id > 0 && !tienePermiso('empleados_editar')) {
    echo 'Error: No tienes permiso para editar empleados';
    exit;
}
if ($id <= 0 && !tienePermiso('empleados_registrar')) {
    echo 'Error: No tienes permiso para registrar empleados';
    exit;
}

// Si el usuario no proporcionó correo/teléfono, usar el valor por defecto de la BD
if ($correo === '') {
    $correo = 'N/D';
}
if ($telefono === '') {
    $telefono = 'N/D';
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
if ($nombre === '' || $apellido === '' || $formacion === '') {
    echo 'Error: Faltan campos requeridos';
    exit;
}

<<<<<<< HEAD
// Validar que nombre y apellido no contengan números
if (preg_match('/\d/', $nombre) || preg_match('/\d/', $apellido)) {
    echo 'Error: El nombre y apellido no pueden contener números';
    exit;
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
$sql = '';
$stmt = null;

if ($id > 0) {
    $sql = "UPDATE empleados
            SET nombre = ?, apellido = ?, formacion = ?, correo = ?, telefono = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo 'Error de conexión al preparar actualización';
        exit;
    }
    $stmt->bind_param('sssssi', $nombre, $apellido, $formacion, $correo, $telefono, $id);
} else {
    $sql = "INSERT INTO empleados (nombre, apellido, formacion, correo, telefono)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo 'Error de conexión al preparar registro';
        exit;
    }
    $stmt->bind_param('sssss', $nombre, $apellido, $formacion, $correo, $telefono);
}

if ($stmt->execute()) {
<<<<<<< HEAD
    if ($id <= 0) $id = (int) $conn->insert_id;
    $accionLog = $id > 0 ? "Editó al empleado ID {$id}" : "Registró al empleado ID {$id}";
    registrar_log($conn, (int) $_SESSION['user_id'], $accionLog);
    $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Empleado añadido correctamente!'];
    echo 'success';
} else {
    error_log('Error MySQL al guardar empleado: ' . $stmt->error);
=======
    echo 'success';
} else {
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    echo 'Error al guardar empleado';
}
?>