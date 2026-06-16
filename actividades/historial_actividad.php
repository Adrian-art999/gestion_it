<?php
date_default_timezone_set('America/Caracas');
session_start();
include '../includes/db.php';
include '../includes/db_schema.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([]);
    exit;
}

header('Content-Type: application/json');
asegurarTablaHistorialActividades($conn);

$actividadId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($actividadId <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT accion, detalle, usuario_nombre, creado_en
        FROM actividad_historial
        WHERE actividad_id = ?
        ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $actividadId);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    if (!empty($row['creado_en'])) {
        $row['creado_en'] = date('d-m-Y H:i', strtotime($row['creado_en']));
    }
    $items[] = $row;
}

echo json_encode($items);
?>
