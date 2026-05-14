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

$credencial = trim((string) ($_POST['credencial'] ?? ''));
$credencialLower = mb_strtolower($credencial, 'UTF-8');
if ($credencial === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Debe indicar username o Gmail']);
    exit;
}

// Búsqueda case-insensitive: tanto username como correo se comparan en minúsculas
$sql = "SELECT u.id, u.username, u.correo, r.pregunta_1, r.pregunta_2, r.pregunta_3
        FROM usuarios u
        LEFT JOIN usuario_recuperacion r ON r.usuario_id = u.id
        WHERE LOWER(TRIM(u.username)) = ? OR LOWER(TRIM(u.correo)) = ?
        LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error SQL: ' . $conn->error]);
    exit;
}
$stmt->bind_param('ss', $credencialLower, $credencialLower);
$stmt->execute();
$res = $stmt->get_result();
$row = $res ? $res->fetch_assoc() : null;

if (!$row) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'No existe una cuenta con esa credencial']);
    exit;
}

if (empty($row['pregunta_1']) || empty($row['pregunta_2']) || empty($row['pregunta_3'])) {
    http_response_code(409);
    echo json_encode(['ok' => false, 'message' => 'La cuenta existe, pero no tiene recuperación configurada']);
    exit;
}

$catalogo = [
    'mascota' => 'Nombre de tu mascota',
    'pelicula' => 'Película favorita',
    'comida' => 'Comida favorita',
    'ciudad_nacimiento' => 'Ciudad de nacimiento',
    'primer_colegio' => 'Primer colegio',
    'cancion_favorita' => 'Canción favorita',
];

$preguntas = [];
for ($i = 1; $i <= 3; $i++) {
    $key = (string) ($row["pregunta_{$i}"] ?? '');
    $preguntas[] = [
        'key' => $key,
        'label' => $catalogo[$key] ?? $key,
    ];
}

echo json_encode([
    'ok' => true,
    'usuario_id' => (int) $row['id'],
    'preguntas' => $preguntas,
]);
?>
