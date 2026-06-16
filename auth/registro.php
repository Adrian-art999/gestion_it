<?php

include '../includes/db.php';

include '../includes/db_schema.php';

asegurarColumnasUsuarios($conn);

asegurarTablaRecuperacionUsuarios($conn);

$mensaje = "";

$nombre = "";

$apellido = "";

$gmail = "";

$formacion = "";

$mensaje = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST['nombre'] ?? '');

    $apellido = trim($_POST['apellido'] ?? '');

    $gmail = trim($_POST['gmail'] ?? '');

    $formacion = trim($_POST['formacion'] ?? '');

    $passPlano = (string) ($_POST['pass1'] ?? '');

    $pregunta1 = trim($_POST['pregunta_1'] ?? '');

    $pregunta2 = trim($_POST['pregunta_2'] ?? '');

    $pregunta3 = trim($_POST['pregunta_3'] ?? '');

    $respuesta1 = trim($_POST['respuesta_1'] ?? '');

    $respuesta2 = trim($_POST['respuesta_2'] ?? '');

    $respuesta3 = trim($_POST['respuesta_3'] ?? '');



    if ($nombre === '' || $apellido === '' || $gmail === '' || $formacion === '' || $passPlano === '') {

        $mensaje = "<div style='color:red'>Debe completar todos los campos obligatorios.</div>";

    } elseif (!preg_match('/^[\p{L}\s]+$/u', $nombre) || !preg_match('/^[\p{L}\s]+$/u', $apellido)) {

        $mensaje = "<div style='color:red'>Nombre y apellido solo permiten letras y espacios.</div>";

    } elseif (!preg_match('/^(?=.*\d).{8,}$/', $passPlano)) {

        $mensaje = "<div style='color:red'>La contraseña debe tener mínimo 8 caracteres y al menos un número.</div>";

    } else {

        $preguntasPermitidas = ['mascota', 'pelicula', 'comida', 'ciudad_nacimiento', 'primer_colegio', 'cancion_favorita'];

        $preguntas = [$pregunta1, $pregunta2, $pregunta3];

        $respuestas = [$respuesta1, $respuesta2, $respuesta3];

        $preguntasValidas = count(array_unique($preguntas)) === 3;

        foreach ($preguntas as $pq) {

            if (!in_array($pq, $preguntasPermitidas, true)) {

                $preguntasValidas = false;

                break;

            }

        }

        foreach ($respuestas as $rp) {

            if ($rp === '') {

                $preguntasValidas = false;

                break;

            }

        }

        if (!$preguntasValidas) {

            $mensaje = "<div style='color:red'>Debes completar 3 preguntas distintas con sus respuestas.</div>";

        }



        $gmailNorm = mb_strtolower(trim($gmail), 'UTF-8');

        $pass = password_hash($passPlano, PASSWORD_DEFAULT);

        $nombreCompleto = trim($nombre . ' ' . $apellido);

        $normalizarRespuesta = static function (string $texto): string {

            $txt = mb_strtolower(trim($texto), 'UTF-8');

            $txt = preg_replace('/\s+/u', '', $txt) ?? $txt;

            return hash('sha256', $txt);

        };

        $resp1Hash = $normalizarRespuesta($respuesta1);

        $resp2Hash = $normalizarRespuesta($respuesta2);

        $resp3Hash = $normalizarRespuesta($respuesta3);



        if ($mensaje !== '') {

            // Validación de preguntas falló.

        } else {

        $username = strstr($gmailNorm, '@', true) ?: $gmailNorm;



        // 1) Unicidad de correo (contra usuarios + empleados)

        $sqlCorreoU = "SELECT id FROM usuarios WHERE LOWER(TRIM(correo)) = ? LIMIT 1";

        $stmtCorreoU = $conn->prepare($sqlCorreoU);

        if (!$stmtCorreoU) {

            $mensaje = "<div style='color:red'>Error de conexión al validar correo: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . "</div>";

        } else {

            $stmtCorreoU->bind_param('s', $gmailNorm);

            $stmtCorreoU->execute();

            if ($stmtCorreoU->get_result()->fetch_assoc()) {

                $mensaje = "<div style='color:red'>El correo electrónico ya está registrado</div>";

            }

        }



        if ($mensaje === '') {

            $sqlCorreoE = "SELECT id FROM empleados WHERE LOWER(TRIM(correo)) = ? LIMIT 1";

            $stmtCorreoE = $conn->prepare($sqlCorreoE);

            if (!$stmtCorreoE) {

                $mensaje = "<div style='color:red'>Error de conexión al validar correo: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . "</div>";

            } else {

                $stmtCorreoE->bind_param('s', $gmailNorm);

                $stmtCorreoE->execute();

                if ($stmtCorreoE->get_result()->fetch_assoc()) {

                    $mensaje = "<div style='color:red'>El correo electrónico ya está registrado</div>";

                }

            }

        }



        // 2) Unicidad de username (solo contra usuarios)

        if ($mensaje === '') {

            $sqlUser = "SELECT id FROM usuarios WHERE username = ? LIMIT 1";

            $stmtUser = $conn->prepare($sqlUser);

            if (!$stmtUser) {

                $mensaje = "<div style='color:red'>Error de conexión al validar username: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8') . "</div>";

            } else {

                $stmtUser->bind_param('s', $username);

                $stmtUser->execute();

                if ($stmtUser->get_result()->fetch_assoc()) {

                    $mensaje = "<div style='color:red'>El username ya está registrado</div>";

                }

            }

        }



        if ($mensaje === '') {

                $sql = "INSERT INTO usuarios (nombre_completo, formacion, correo, username, password, rol)

                        VALUES (?, ?, ?, ?, ?, 'tecnico')";

                $stmt = $conn->prepare($sql);

                if (!$stmt) {

                    $mensaje = "<div style='color:red'>Error de conexión al preparar el registro.</div>";

                } else {

                    $stmt->bind_param('sssss', $nombreCompleto, $formacion, $gmailNorm, $username, $pass);



                    try {

                        $conn->begin_transaction();

                        if (!$stmt->execute()) {

                            throw new RuntimeException($stmt->error ?: 'Error desconocido al registrar');

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

                            throw new RuntimeException($stmtRec->error ?: 'Error al guardar recuperación');

                        }

                        $conn->commit();

                        $mensaje = "<div style='color:#155724; background:#d4edda; padding:10px; border-radius:4px; margin-bottom:10px; border:1px solid #c3e6cb;'>✅ ¡Empleado registrado con éxito! <a href='login.php'>Ir al Login</a></div>";

                    } catch (Throwable $e) {

                        $conn->rollback();

                        $detalle = $e->getMessage();

                        $errno = (int) ($conn->errno ?? 0);

                        if ($errno === 1062 || stripos($detalle, 'Duplicate') !== false) {

                            if (stripos($detalle, 'correo') !== false) {

                                $detalle = 'Gmail duplicado: ya existe ese correo.';

                            } elseif (stripos($detalle, 'username') !== false) {

                                $detalle = 'Username duplicado: ya existe ese usuario.';

                            } else {

                                $detalle = 'Registro duplicado en campo único.';

                            }

                        } elseif (stripos($detalle, 'Unknown column') !== false || stripos($detalle, 'no such column') !== false) {

                            $detalle = 'Columna no encontrada en la tabla usuarios.';

                        }

                        $mensaje = "<div style='color:red'>Error al registrar usuario: " . htmlspecialchars($detalle, ENT_QUOTES, 'UTF-8') . "</div>";

                    }

                }

        }

        }

    }

}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>Registro IT - Secretaría de Salud</title>

    <style>

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; display: flex; justify-content: center; padding-top: 50px; }

        .box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 400px; }

        h2 { color: #1a73e8; text-align: center; margin-top: 0; }

        input, select { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 14px; }

        button { width: 100%; padding: 12px; background: #1a73e8; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s; }

        button:hover { background: #1557b0; }

        .footer-link { text-align: center; margin-top: 15px; font-size: 13px; }

        .footer-link a { color: #1a73e8; text-decoration: none; }

    </style>

</head>

<body>

    <div class="box">

        <h2>Registrar Personal</h2>

        <?php echo $mensaje; ?>

        <form method="POST">

            <input type="text" name="nombre" placeholder="Nombre" required>

            <input type="text" name="apellido" placeholder="Apellido" required>

            <input type="email" name="gmail" placeholder="Gmail" required>

            <input type="text" name="formacion" placeholder="Cargo (Ej: Técnico de Soporte)" required>

            <input type="password" name="pass1" placeholder="Contraseña" required>

            <small style="display:block; margin:0 0 8px; color:#5f6368;">Mínimo 8 caracteres y al menos un número.</small>

            <select name="pregunta_1" required>

                <option value="">Pregunta 1</option>

                <option value="mascota">Nombre de tu mascota</option>

                <option value="pelicula">Película favorita</option>

                <option value="comida">Comida favorita</option>

                <option value="ciudad_nacimiento">Ciudad de nacimiento</option>

                <option value="primer_colegio">Primer colegio</option>

                <option value="cancion_favorita">Canción favorita</option>

            </select>

            <input type="text" name="respuesta_1" placeholder="Respuesta 1" required>

            <select name="pregunta_2" required>

                <option value="">Pregunta 2</option>

                <option value="mascota">Nombre de tu mascota</option>

                <option value="pelicula">Película favorita</option>

                <option value="comida">Comida favorita</option>

                <option value="ciudad_nacimiento">Ciudad de nacimiento</option>

                <option value="primer_colegio">Primer colegio</option>

                <option value="cancion_favorita">Canción favorita</option>

            </select>

            <input type="text" name="respuesta_2" placeholder="Respuesta 2" required>

            <select name="pregunta_3" required>

                <option value="">Pregunta 3</option>

                <option value="mascota">Nombre de tu mascota</option>

                <option value="pelicula">Película favorita</option>

                <option value="comida">Comida favorita</option>

                <option value="ciudad_nacimiento">Ciudad de nacimiento</option>

                <option value="primer_colegio">Primer colegio</option>

                <option value="cancion_favorita">Canción favorita</option>

            </select>

            <input type="text" name="respuesta_3" placeholder="Respuesta 3" required>

            <button type="submit">Crear Cuenta</button>

        </form>

        <div class="footer-link">

            ¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a>

        </div>

    </div>

<script>

    (function () {

        const selects = Array.from(document.querySelectorAll('select[name^="pregunta_"]'));

        if (selects.length < 2) return;

        function refresh() {

            const selected = selects.map(s => s.value).filter(Boolean);

            selects.forEach(sel => {

                Array.from(sel.options).forEach(opt => {

                    if (!opt.value) return;

                    opt.disabled = (opt.value !== sel.value && selected.includes(opt.value));

                });

            });

        }

        selects.forEach(s => s.addEventListener('change', refresh));

        refresh();

    })();

</script>

</body>

</html>

</html>