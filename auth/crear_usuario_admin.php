<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/permisos.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Sesión vencida']);
    exit;
}

if (!tienePermiso('usuarios_registrar')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'message' => 'No tienes permiso para esta acción']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Método no permitido']);
    exit;
}

// ── Captura y saneamiento ──────────────────────────────────────────────────
$nombre    = trim($_POST['nombre']    ?? '');
$apellido  = trim($_POST['apellido']  ?? '');
$username  = trim($_POST['username']  ?? '');
$password  = (string) ($_POST['password'] ?? '');
$correo    = trim($_POST['correo']    ?? '');
$telefono  = trim($_POST['telefono']  ?? '');
$formacion = trim($_POST['formacion'] ?? '');
$pregunta1 = trim($_POST['pregunta_1'] ?? '');
$pregunta2 = trim($_POST['pregunta_2'] ?? '');
$pregunta3 = trim($_POST['pregunta_3'] ?? '');
$respuesta1 = trim($_POST['respuesta_1'] ?? '');
$respuesta2 = trim($_POST['respuesta_2'] ?? '');
$respuesta3 = trim($_POST['respuesta_3'] ?? '');

// ── Validaciones de campos requeridos ─────────────────────────────────────
// Verificación explícita de username vacío → 400 Bad Request
if ($username === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'El campo username es obligatorio y no puede estar vacío']);
    exit;
}
if ($nombre === '' || $apellido === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Faltan campos requeridos (nombre, apellido o contraseña)']);
    exit;
}

// Validar que username no tenga caracteres problemáticos (solo alfanumérico + _ -)
if (!preg_match('/^[a-zA-Z0-9_\-\.]{3,60}$/', $username)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'El username solo permite letras, números, guiones y puntos (3-60 caracteres)']);
    exit;
}

$regexNombre = '/^[\p{L}\s]+$/u';
if (!preg_match($regexNombre, $nombre) || !preg_match($regexNombre, $apellido)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Nombre y apellido solo permiten letras y espacios']);
    exit;
}

if (!preg_match('/^(?=.*\d).{8,}$/', $password)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'La contraseña debe tener mínimo 8 caracteres y al menos un número']);
    exit;
}

// ── Validación de preguntas de seguridad ──────────────────────────────────
$preguntasPermitidas = ['mascota', 'pelicula', 'comida', 'ciudad_nacimiento', 'primer_colegio', 'cancion_favorita'];
$preguntas  = [$pregunta1, $pregunta2, $pregunta3];
$respuestas = [$respuesta1, $respuesta2, $respuesta3];

foreach ($preguntas as $i => $pregunta) {
    if (!in_array($pregunta, $preguntasPermitidas, true)) {
        echo json_encode(['ok' => false, 'message' => 'Pregunta de seguridad inválida en la posición ' . ($i + 1)]);
        exit;
    }
}
if (count(array_unique($preguntas)) !== 3) {
    echo json_encode(['ok' => false, 'message' => 'Debes seleccionar 3 preguntas diferentes']);
    exit;
}
foreach ($respuestas as $i => $resp) {
    if ($resp === '') {
        echo json_encode(['ok' => false, 'message' => 'Respuesta vacía en la posición ' . ($i + 1)]);
        exit;
    }
}

// ── Preparar datos finales ─────────────────────────────────────────────────
$nombreCompleto = $nombre . ' ' . $apellido;
$correoNorm     = $correo !== '' ? mb_strtolower($correo, 'UTF-8') : '';

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
if ($passwordHash === false) {
    echo json_encode(['ok' => false, 'message' => 'No se pudo hashear la contraseña']);
    exit;
}

$normalizarRespuesta = static function (string $texto): string {
    $txt = mb_strtolower(trim($texto), 'UTF-8');
    $txt = preg_replace('/\s+/u', '', $txt) ?? $txt;
    return hash('sha256', $txt);
};
$resp1Hash = $normalizarRespuesta($respuesta1);
$resp2Hash = $normalizarRespuesta($respuesta2);
$resp3Hash = $normalizarRespuesta($respuesta3);

// ── Verificar duplicados ANTES de la transacción ──────────────────────────
try {
    $existeUsername = false;
    $existeCorreo   = false;

    $stmtUsername = $conn->prepare("SELECT id FROM usuarios WHERE username = ? LIMIT 1");
    if ($stmtUsername) {
        $stmtUsername->bind_param('s', $username);
        $stmtUsername->execute();
        if ($stmtUsername->get_result()->fetch_assoc()) {
            $existeUsername = true;
        }
    }

    if ($correoNorm !== '') {
        $stmtCU = $conn->prepare("SELECT id FROM usuarios WHERE correo IS NOT NULL AND LOWER(TRIM(correo)) = ? LIMIT 1");
        if ($stmtCU) {
            $stmtCU->bind_param('s', $correoNorm);
            $stmtCU->execute();
            if ($stmtCU->get_result()->fetch_assoc()) {
                $existeCorreo = true;
            }
        }
        if (!$existeCorreo) {
            $stmtCE = $conn->prepare("SELECT id FROM empleados WHERE correo IS NOT NULL AND LOWER(TRIM(correo)) = ? LIMIT 1");
            if ($stmtCE) {
                $stmtCE->bind_param('s', $correoNorm);
                $stmtCE->execute();
                if ($stmtCE->get_result()->fetch_assoc()) {
                    $existeCorreo = true;
                }
            }
        }
    }

    if ($existeUsername) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'El nombre de usuario ya está en uso']);
        exit;
    }
    if ($existeCorreo) {
        http_response_code(409);
        echo json_encode(['ok' => false, 'message' => 'El correo ya está registrado en el sistema']);
        exit;
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'message' => 'Error de validación: ' . $e->getMessage()]);
    exit;
}

// ── Insertar usuario y datos de recuperación en transacción ───────────────
$sql = "INSERT INTO usuarios (nombre_completo, formacion, correo, telefono, username, password, rol)
        VALUES (?, ?, ?, ?, ?, ?, 'tecnico')";
try {
    $conn->begin_transaction();

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new RuntimeException('No se pudo preparar el registro: ' . $conn->error);
    }
    $stmt->bind_param('ssssss', $nombreCompleto, $formacion, $correoNorm, $telefono, $username, $passwordHash);
    if (!$stmt->execute()) {
        throw new RuntimeException($stmt->error, $stmt->errno);
    }

    $idUsuario = (int) $conn->insert_id;

    $sqlRec = "INSERT INTO usuario_recuperacion
               (usuario_id, pregunta_1, respuesta_1_hash, pregunta_2, respuesta_2_hash, pregunta_3, respuesta_3_hash)
               VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtRec = $conn->prepare($sqlRec);
    if (!$stmtRec) {
        throw new RuntimeException('No se pudo preparar recuperación: ' . $conn->error);
    }
    $stmtRec->bind_param('issssss', $idUsuario, $pregunta1, $resp1Hash, $pregunta2, $resp2Hash, $pregunta3, $resp3Hash);
    if (!$stmtRec->execute()) {
        throw new RuntimeException($stmtRec->error, $stmtRec->errno);
    }

    $conn->commit();
} catch (Throwable $e) {
    $conn->rollback();
    $errno   = (int) $e->getCode();
    $mensaje = $e->getMessage();

    if ($errno === 1062 || stripos($mensaje, 'Duplicate entry') !== false) {
        http_response_code(409);
        // Distinguir qué campo duplica para dar feedback preciso
        if (stripos($mensaje, 'username') !== false || stripos($mensaje, 'idx_unique_username') !== false) {
            echo json_encode(['ok' => false, 'message' => 'El nombre de usuario ya está en uso']);
        } else {
            echo json_encode(['ok' => false, 'message' => 'Datos duplicados: el correo o usuario ya existen']);
        }
    } elseif (stripos($mensaje, 'Unknown column') !== false) {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Error de estructura en BD. Contacta al administrador.']);
    } else {
        http_response_code(500);
        echo json_encode(['ok' => false, 'message' => 'Error al registrar usuario: ' . $mensaje]);
    }
    exit;
}

registrar_log($conn, (int) $_SESSION['user_id'], "Registró al usuario ID {$idUsuario}");
$_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Usuario guardado!'];
echo json_encode(['ok' => true, 'message' => 'Usuario registrado correctamente']);
?>
