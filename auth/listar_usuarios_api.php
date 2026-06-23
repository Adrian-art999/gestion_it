<?php
include '../includes/config.php';

if (!tienePermiso('usuarios_listar')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'No tienes permiso para esta acción']);
    exit;
}


$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
$sql = "SELECT id,
               nombre_completo,
               COALESCE(NULLIF(formacion, ''), 'N/D') AS formacion,
               username,
               COALESCE(NULLIF(correo, ''), 'N/D') AS correo,
               COALESCE(NULLIF(telefono, ''), 'N/D') AS telefono
        FROM usuarios";

if (!empty($busqueda)) {
    $sql .= " WHERE nombre_completo LIKE ? OR username LIKE ? OR formacion LIKE ?";
    $sql .= " ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $param = '%' . $busqueda . '%';
    $stmt->bind_param('sss', $param, $param, $param);
} else {
    $sql .= " ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

$usuarios = [];
while ($row = $result->fetch_assoc()) {
    $usuarios[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['usuarios' => $usuarios]);
