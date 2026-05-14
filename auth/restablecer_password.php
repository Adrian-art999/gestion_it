<?php
declare(strict_types=1);

require_once '../includes/db.php';
require_once '../includes/db_schema.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
    exit;
}

asegurarTablaRecuperacionUsuarios($conn);

$usuarioId = (int) ($_POST['usuario_id'] ?? 0);
$nuevaPassword = (string) ($_POST['nueva_password'] ?? '');
$r1 = trim((string) ($_POST['respuesta_1'] ?? ''));
$r2 = trim((string) ($_POST['respuesta_2'] ?? ''));
$r3 = trim((string) ($_POST['respuesta_3'] ?? ''));
$validarRespuestas = (string) ($_POST['validar_respuestas'] ?? '');

if ($usuarioId <= 0) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Datos incompletos para recuperar cuenta']);
    exit;
}

// Modo de validación: solo verificar respuestas sin cambiar contraseña
if ($validarRespuestas === '1') {
    if ($r1 === '' || $r2 === '' || $r3 === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Debes responder las 3 preguntas']);
        exit;
    }
} else {
    // Modo de cambio: requiere contraseña
    if ($nuevaPassword === '') {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'Datos incompletos para recuperar cuenta']);
        exit;
    }
}

if ($validarRespuestas !== '1') {
    // Validar contraseña solo en modo de cambio
    if (!preg_match('/^(?=.*\d).{8,}$/', $nuevaPassword)) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'message' => 'La nueva contraseña debe tener mínimo 8 caracteres y al menos un número']);
        exit;
    }
}

$sql = "SELECT respuesta_1_hash, respuesta_2_hash, respuesta_3_hash
        FROM usuario_recuperacion
        WHERE usuario_id = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error SQL: ' . $conn->error]);
    exit;
}
$stmt->bind_param('i', $usuarioId);
$stmt->execute();
$res = $stmt->get_result();
$rec = $res ? $res->fetch_assoc() : null;
if (!$rec) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'No se encontró configuración de recuperación para este usuario']);
    exit;
}

$normalizar = static function (string $txt): string {
    $v = mb_strtolower(trim($txt), 'UTF-8');
    $v = preg_replace('/\s+/u', '', $v) ?? $v;
    return hash('sha256', $v);
};

$hashesIngresados = [
    $normalizar($r1),
    $normalizar($r2),
    $normalizar($r3),
];
$hashesGuardados = [
    (string) ($rec['respuesta_1_hash'] ?? ''),
    (string) ($rec['respuesta_2_hash'] ?? ''),
    (string) ($rec['respuesta_3_hash'] ?? ''),
];

$aciertos = 0;
for ($i = 0; $i < 3; $i++) {
    if ($hashesIngresados[$i] !== '' && hash_equals($hashesGuardados[$i], $hashesIngresados[$i])) {
        $aciertos++;
    }
}

// Requiere al menos 2 de 3 respuestas correctas
if ($aciertos < 2) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'Respuestas de seguridad incorrectas. Necesitas al menos 2 respuestas correctas']);
    exit;
}

// Si es modo de validación, solo retornar éxito sin cambiar contraseña
if ($validarRespuestas === '1') {
    echo json_encode(['ok' => true, 'message' => 'Respuestas correctas. Puede continuar']);
    exit;
}

$passwordHash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
if ($passwordHash === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'No se pudo procesar la nueva contraseña']);
    exit;
}

$sqlUpdate = "UPDATE usuarios SET password = ? WHERE id = ? LIMIT 1";
$stmtUpdate = $conn->prepare($sqlUpdate);
if (!$stmtUpdate) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error SQL: ' . $conn->error]);
    exit;
}
$stmtUpdate->bind_param('si', $passwordHash, $usuarioId);
if (!$stmtUpdate->execute()) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error SQL: ' . $stmtUpdate->error]);
    exit;
}

echo json_encode(['ok' => true, 'message' => 'Contraseña restablecida correctamente']);
?>
