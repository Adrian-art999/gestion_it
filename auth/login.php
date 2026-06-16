<?php
session_start();
include '../includes/db.php';
include '../includes/db_schema.php';
include '../includes/security.php';
include '../includes/permisos.php';
asegurarTablaRecuperacionUsuarios($conn);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credencial = trim($_POST['correo'] ?? '');
    $pass       = (string) ($_POST['pass'] ?? '');

    $query = "SELECT * FROM usuarios WHERE correo = ? OR username = ? LIMIT 1";
    $stmt  = $conn->prepare($query);
    $stmt->bind_param('ss', $credencial, $credencial);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $passOk = ($pass !== '' && $user['password'] !== '')
            && ($pass === $user['password'] || password_verify($pass, $user['password']));

        if ($passOk) {
            $_SESSION['user_id']       = $user['id'];
            $_SESSION['nombre']        = $user['nombre_completo'] ?? $user['username'] ?? 'Usuario';
            $_SESSION['rol']           = $user['rol'] ?? 'admin';

            // Asegurar columna permisos y cargar permisos del usuario
            asegurarColumnaPermisos($conn);
            cargarPermisosEnSesion($conn, (int) $user['id'], $_SESSION['nombre'], $_SESSION['rol']);

            header('Location: ../dashboard.php');
            exit();
        } else {
            $error = 'Contraseña incorrecta. Inténtalo de nuevo.';
        }
    } else {
        $error = 'No existe ninguna cuenta con ese correo o username.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema O.S.T.I</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: #fff;
            padding: 36px 32px 28px;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(60,64,67,0.13);
            width: 400px;
            text-align: center;
        }
        .logo {
            font-size: 22px;
            color: #1a73e8;
            font-weight: 700;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .logo .material-icons { font-size: 28px; }
        h2 { color: #202124; margin-bottom: 20px; font-size: 20px; font-weight: 500; }
        .input-wrapper {
            position: relative;
            margin: 8px 0;
        }
        .input-wrapper .material-icons {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #5f6368;
            font-size: 20px;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 12px 14px 12px 44px;
            margin: 0;
            border: 1px solid #dadce0;
            border-radius: 8px;
            outline: none;
            font-size: 15px;
            transition: border-color .2s;
            background: #fff;
        }
        input:focus { border-color: #1a73e8; border-width: 2px; }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #1a73e8;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 14px;
            font-size: 15px;
            transition: background .2s;
        }
        .btn-primary:hover { background: #1557b0; }
        .error-msg {
            color: #d93025;
            background: #fce8e6;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 14px;
            text-align: left;
            display: <?php echo $error ? 'block' : 'none'; ?>;
        }
        .link-recover {
            background: none;
            border: none;
            color: #1a73e8;
            cursor: pointer;
            font-size: 13px;
            margin-top: 14px;
            text-decoration: underline;
        }
        .footer-note { margin-top: 20px; font-size: 12px; color: #9aa0a6; }

        /* ── Modal de recuperación ── */
        .rec-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            z-index: 9000;
            justify-content: center;
            align-items: center;
        }
        .rec-overlay.active { display: flex; }
        .rec-box {
            background: #fff;
            border-radius: 16px;
            padding: 32px 28px 24px;
            width: 420px;
            max-width: 94vw;
            box-shadow: 0 8px 32px rgba(0,0,0,.18);
            position: relative;
            max-height: 90vh;
            overflow-y: auto;
        }
        .rec-box h3 {
            font-size: 17px;
            font-weight: 600;
            color: #1a73e8;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
        }
        .rec-step { display: none; }
        .rec-step.active { display: block; }
        .step-label {
            font-size: 12px;
            font-weight: 700;
            color: #9aa0a6;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 12px;
        }
        .rec-box label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #3c4043;
            margin-bottom: 4px;
            margin-top: 12px;
        }
        .rec-box input[type=text],
        .rec-box input[type=password] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s;
            margin: 0;
        }
        .rec-box input[readonly] { background: #f8f9fa; color: #5f6368; font-weight: 500; }
        .rec-box input:focus:not([readonly]) { border-color: #1a73e8; border-width: 2px; }
        .rec-msg {
            font-size: 13px;
            padding: 9px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            display: none;
        }
        .rec-msg.error { background: #fce8e6; color: #b3261e; }
        .rec-msg.ok    { background: #e6f4ea; color: #137333; }
        .rec-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            justify-content: flex-end;
        }
        .btn-rec-primary {
            background: #1a73e8; color: #fff; border: none;
            padding: 10px 20px; border-radius: 8px;
            font-weight: 600; cursor: pointer; font-size: 14px;
            transition: background .2s;
        }
        .btn-rec-primary:hover { background: #1557b0; }
        .btn-rec-secondary {
            background: #f1f3f4; color: #3c4043; border: none;
            padding: 10px 18px; border-radius: 8px;
            font-weight: 500; cursor: pointer; font-size: 14px;
        }
        .btn-rec-secondary:hover { background: #e8eaed; }
        .btn-close-rec {
            position: absolute; top: 16px; right: 18px;
            background: none; border: none; cursor: pointer;
            color: #5f6368; display: flex; align-items: center;
        }
        .password-hint { font-size: 12px; color: #5f6368; margin-top: 4px; }
        /* ── Mega Menú de Documentación ── */
        .doc-menu-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            padding: 12px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #0d47a1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all .3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .doc-menu-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }
        .mega-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(30px);
            padding: 60px 80px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            z-index: 999;
            animation: slideDown 0.4s ease-out;
        }
        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .mega-menu.active { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 60px; max-width: 1400px; margin: 0 auto; }
        .mega-menu-column h4 {
            color: #1a73e8;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            border-bottom: 2px solid #1a73e8;
            padding-bottom: 10px;
        }
        .mega-menu-column p {
            color: #3c4043;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 14px;
        }
        .mega-menu-column strong { color: #1a73e8; }
    </style>
</head>
<body>
    <button class="doc-menu-btn" id="docMenuBtn">
        Documentación ☰
    </button>
    <div class="mega-menu" id="megaMenu">
        <div class="mega-menu-column">
            <h4>Acceso</h4>
            <p>Puedes iniciar sesión usando tu <strong>Username</strong> o tu <strong>Gmail</strong> registrado en el sistema.</p>
            <p>Si olvidaste tu contraseña, utiliza el sistema de recuperación basado en <strong>preguntas de seguridad</strong> predefinidas.</p>
            <p>Para restablecer tu contraseña debes responder 2/3 Respuestas Correctas</p>
        </div>
        <div class="mega-menu-column">
            <h4>Operaciones</h4>
            <p>El sistema implementa operaciones <strong>CRUD</strong> completas para empleados, usuarios y actividades.</p>
            <p>Se mantiene una <strong>democracia de permisos</strong>: todos los usuarios pueden editar y eliminar registros.</p>
            <p>Los campos vacíos se muestran como <strong>N/D</strong> (No Disponible) para mantener la integridad de datos.</p>
        </div>
        <div class="mega-menu-column">
            <h4>Soporte</h4>
            <p><strong>O.S.T.I.</strong> significa <em>Operaciones de Sistemas de Tecnología de Información</em>.</p>
            <p>Sistema de gestión para el departamento técnico de la Secretaría de Salud.</p>
            <p><strong>Versión 1.5</strong> - Actualizado con interfaz moderna y mejoras de seguridad.</p>
        </div>
        <div class="mega-menu-column">
            <h4>Creación</h4>
            <p><strong>Frontend:</strong> HTML5, CSS3, JavaScript, Material Icons.</p>
            <p><strong>Backend:</strong> PHP 8, MySQLi, Prepared Statements.</p>
            <p><strong>Seguridad:</strong> Password hashing, Session management, SQL injection prevention.</p>
        </div>
    </div>
    <div class="login-box">
        <div class="logo">
            <span class="material-icons">dns</span> Sistema O.S.T.I
        </div>
        <h2>Acceso al sistema</h2>

        <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>

        <form method="POST" autocomplete="on">
            <div class="input-wrapper">
                <span class="material-icons">person</span>
                <input type="text"     name="correo" placeholder="Correo o username" required autocomplete="username">
            </div>
            <div class="input-wrapper">
                <span class="material-icons">lock</span>
                <input type="password" name="pass"   placeholder="Contraseña"        required autocomplete="current-password">
            </div>
            <button type="submit" class="btn-primary">INICIAR</button>
        </form>

        <button type="button" class="link-recover" id="btnAbrirRecuperar">
            ¿Olvidaste tu contraseña?
        </button>

        <p class="footer-note">Uso exclusivo para personal técnico del departamento.</p>
    </div>

    <!-- ══ Modal de recuperación de contraseña ══ -->
    <div class="rec-overlay" id="recOverlay" role="dialog" aria-modal="true" aria-labelledby="recTitulo">
        <div class="rec-box">
            <button class="btn-close-rec" id="btnCerrarRec" aria-label="Cerrar">
                <span class="material-icons">close</span>
            </button>

            <h3 id="recTitulo">
                <span class="material-icons">lock_reset</span>
                Recuperar contraseña
            </h3>

            <div class="rec-msg" id="recMsg"></div>

            <!-- Paso 1 -->
            <div class="rec-step active" id="recPaso1">
                <div class="step-label">Paso 1 de 3 — Identificar cuenta</div>
                <label for="recCredencial">Username o correo</label>
                <input type="text" id="recCredencial" placeholder="ej: manuel02 o usuario@gmail.com" autocomplete="off">
                <div class="rec-actions">
                    <button class="btn-rec-secondary" id="btnCancelarPaso1">Cancelar</button>
                    <button class="btn-rec-primary"   id="btnBuscarCuenta">Continuar</button>
                </div>
            </div>

            <!-- Paso 2 -->
            <div class="rec-step" id="recPaso2">
                <div class="step-label">Paso 2 de 3 — Preguntas de seguridad</div>
                <p style="font-size:13px;color:#5f6368;margin-bottom:8px;"> respuestas deben ser correctas para continuar.</p>

                <label>Pregunta 1</label>
                <input type="text" id="pregunta1Label" readonly>
                <label>Respuesta 1</label>
                <input type="password" id="respuesta1" placeholder="Tu respuesta" autocomplete="off">

                <label>Pregunta 2</label>
                <input type="text" id="pregunta2Label" readonly>
                <label>Respuesta 2</label>
                <input type="password" id="respuesta2" placeholder="Tu respuesta" autocomplete="off">

                <label>Pregunta 3</label>
                <input type="text" id="pregunta3Label" readonly>
                <label>Respuesta 3</label>
                <input type="password" id="respuesta3" placeholder="Tu respuesta" autocomplete="off">

                <div class="rec-actions">
                    <button class="btn-rec-secondary" id="btnAtrasP2">Atrás</button>
                    <button class="btn-rec-primary"   id="btnValidarPreguntas">Validar respuestas</button>
                </div>
            </div>

            <!-- Paso 3 -->
            <div class="rec-step" id="recPaso3">
                <div class="step-label">Paso 3 de 3 — Nueva contraseña</div>
                <label for="nuevaPassword">Nueva contraseña</label>
                <input type="password" id="nuevaPassword" placeholder="Mínimo 8 caracteres y un número" autocomplete="new-password">
                <div class="password-hint">Mínimo 8 caracteres y al menos un número.</div>
                <div class="rec-actions">
                    <button class="btn-rec-primary" id="btnCambiarPassword">Cambiar contraseña</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        // Mega Menú toggle
        var docMenuBtn = document.getElementById('docMenuBtn');
        var megaMenu = document.getElementById('megaMenu');
        docMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            megaMenu.classList.toggle('active');
        });
        document.addEventListener('click', function(e) {
            if (!megaMenu.contains(e.target) && !docMenuBtn.contains(e.target)) {
                megaMenu.classList.remove('active');
            }
        });

        var overlay   = document.getElementById('recOverlay');
        var msg       = document.getElementById('recMsg');
        var pasos     = ['recPaso1', 'recPaso2', 'recPaso3'];
        var usuarioId = 0;

        function mostrarPaso(id) {
            pasos.forEach(function (p) {
                var el = document.getElementById(p);
                if (el) el.className = 'rec-step' + (p === id ? ' active' : '');
            });
            limpiarMsg();
        }
        function mostrarMsg(texto, esOk) {
            msg.textContent   = texto;
            msg.className     = 'rec-msg ' + (esOk ? 'ok' : 'error');
            msg.style.display = 'block';
        }
        function limpiarMsg() {
            msg.style.display = 'none';
            msg.textContent   = '';
        }
        function abrirModal() {
            overlay.classList.add('active');
            mostrarPaso('recPaso1');
            document.getElementById('recCredencial').value = '';
            ['respuesta1','respuesta2','respuesta3','nuevaPassword'].forEach(function(id){
                var el = document.getElementById(id);
                if (el) el.value = '';
            });
            usuarioId = 0;
        }
        function cerrarModal() {
            overlay.classList.remove('active');
            limpiarMsg();
        }

        document.getElementById('btnAbrirRecuperar').addEventListener('click', abrirModal);
        document.getElementById('btnCerrarRec').addEventListener('click', cerrarModal);
        document.getElementById('btnCancelarPaso1').addEventListener('click', cerrarModal);
        document.getElementById('btnAtrasP2').addEventListener('click', function () { mostrarPaso('recPaso1'); });
        overlay.addEventListener('click', function (e) { if (e.target === overlay) cerrarModal(); });

        /* Paso 1 — buscar cuenta */
        document.getElementById('btnBuscarCuenta').addEventListener('click', async function () {
            limpiarMsg();
            var credencial = document.getElementById('recCredencial').value.trim();
            if (!credencial) { mostrarMsg('Debes ingresar tu username o correo.'); return; }
            this.disabled = true;
            try {
                var fd = new FormData();
                fd.append('credencial', credencial);
                var res  = await fetch('recuperar_preguntas.php', { method: 'POST', body: fd });
                var data = await res.json();
                if (!data.ok) { mostrarMsg(data.message || 'No se pudo encontrar la cuenta.'); return; }
                usuarioId = Number(data.usuario_id || 0);
                var pregs = data.preguntas || [];
                document.getElementById('pregunta1Label').value = pregs[0]?.label || '';
                document.getElementById('pregunta2Label').value = pregs[1]?.label || '';
                document.getElementById('pregunta3Label').value = pregs[2]?.label || '';
                mostrarPaso('recPaso2');
            } catch (err) {
                mostrarMsg('Error de conexión. Intenta de nuevo.');
            } finally { this.disabled = false; }
        });

        /* Paso 2 — validar  respuestas (TODAS deben ser correctas) */
        document.getElementById('btnValidarPreguntas').addEventListener('click', async function () {
            limpiarMsg();
            if (!usuarioId) { mostrarMsg('Sesión inválida. Vuelve al paso 1.'); return; }
            var r1 = document.getElementById('respuesta1').value;
            var r2 = document.getElementById('respuesta2').value;
            var r3 = document.getElementById('respuesta3').value;
            if (!r1 || !r2 || !r3) { mostrarMsg('Debes responder las 3 preguntas para continuar.'); return; }
            this.disabled = true;
            try {
                var fd = new FormData();
                fd.append('usuario_id', String(usuarioId));
                fd.append('respuesta_1', r1);
                fd.append('respuesta_2', r2);
                fd.append('respuesta_3', r3);
                fd.append('validar_respuestas', '1');
                var res  = await fetch('restablecer_password.php', { method: 'POST', body: fd });
                var data = await res.json();
                if (!data.ok) {
                    ['respuesta1','respuesta2','respuesta3'].forEach(function(id){
                        document.getElementById(id).value = '';
                    });
                    mostrarMsg(data.message || 'Una o más respuestas son incorrectas. Intenta de nuevo.');
                    return;
                }
                mostrarPaso('recPaso3');
            } catch (err) {
                mostrarMsg('Error de conexión. Intenta de nuevo.');
            } finally { this.disabled = false; }
        });

        /* Paso 3 — cambiar contraseña */
        document.getElementById('btnCambiarPassword').addEventListener('click', async function () {
            limpiarMsg();
            var nueva = document.getElementById('nuevaPassword').value;
            if (!nueva) { mostrarMsg('Ingresa la nueva contraseña.'); return; }
            this.disabled = true;
            try {
                var fd = new FormData();
                fd.append('usuario_id', String(usuarioId));
                fd.append('respuesta_1', document.getElementById('respuesta1').value);
                fd.append('respuesta_2', document.getElementById('respuesta2').value);
                fd.append('respuesta_3', document.getElementById('respuesta3').value);
                fd.append('nueva_password', nueva);
                var res  = await fetch('restablecer_password.php', { method: 'POST', body: fd });
                var data = await res.json();
                if (!data.ok) { mostrarMsg(data.message || 'No se pudo cambiar la contraseña.'); return; }
                mostrarMsg('¡Contraseña actualizada correctamente! Ya puedes iniciar sesión.', true);
                setTimeout(cerrarModal, 2800);
            } catch (err) {
                mostrarMsg('Error de conexión. Intenta de nuevo.');
            } finally { this.disabled = false; }
        });
    })();
    </script>
</body>
</html>