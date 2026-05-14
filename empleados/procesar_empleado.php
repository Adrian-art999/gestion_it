<?php
session_start();
include '../includes/db.php';
include '../includes/db_schema.php';

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

if ($nombre === '' || $apellido === '' || $formacion === '') {
    echo 'Error: Faltan campos requeridos';
    exit;
}

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
    echo 'success';
} else {
    echo 'Error al guardar empleado';
}
?>