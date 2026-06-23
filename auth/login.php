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

        /* ── Mega Menú Documentación (estilo Discord) ── */
        .doc-wrapper {
            position: fixed;
            top: 18px;
            right: 22px;
            z-index: 9999;
        }
        .doc-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(0,0,0,0.55);
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            backdrop-filter: blur(6px);
            transition: background 0.2s, transform 0.15s;
        }
        .doc-btn:hover { background: rgba(0,0,0,0.7); transform: scale(1.05); }
        .doc-btn .material-icons { font-size: 22px; }
        .doc-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 860px;
            background: #1e1f22;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 14px 35px rgba(0,0,0,0.4);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition: opacity 0.25s ease, visibility 0.25s ease, transform 0.25s ease;
        }
        .doc-wrapper:hover .doc-dropdown,
        .doc-dropdown.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .doc-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        .doc-col-title {
            font-size: 10px;
            font-weight: 700;
            color: #949ba4;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid #2b2d31;
        }
        .doc-card {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            color: #dbdee1;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
            cursor: pointer;
            margin-bottom: 6px;
        }
        .doc-card:hover { background: #2b2d31; }
        .doc-card .material-icons {
            font-size: 20px;
            color: #5865f2;
            margin-top: 1px;
            flex-shrink: 0;
        }
        .doc-card-text { display: flex; flex-direction: column; min-width: 0; }
        .doc-card-title {
            font-weight: 500;
            color: #f2f3f5;
            font-size: 13px;
        }
        .doc-card-desc {
            font-size: 11px;
            color: #949ba4;
            line-height: 1.4;
            margin-top: 2px;
        }
        /* ── Modal flotante personalizado ── */
        .doc-modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }
        .doc-modal-overlay.show { display: flex; }
        .doc-modal {
            background: #1e1f22;
            border-radius: 20px;
            width: 560px;
            max-width: 92vw;
            max-height: 80vh;
            box-shadow: 0 16px 40px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            animation: docModalIn 0.2s ease;
        }
        @keyframes docModalIn {
            from { opacity: 0; transform: scale(0.95) translateY(8px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }
        .doc-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px 0;
        }
        .doc-modal-header h3 {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #f2f3f5;
        }
        .doc-modal-header h3 .material-icons { font-size: 24px; color: #5865f2; }
        .doc-modal-close {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            color: #949ba4;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            transition: background 0.2s, color 0.2s;
        }
        .doc-modal-close:hover { background: #2b2d31; color: #fff; }
        .doc-modal-body {
            padding: 16px 24px 24px;
            overflow-y: auto;
            font-size: 14px;
            line-height: 1.7;
            color: #b5bac1;
        }
        .doc-modal-body strong { color: #f2f3f5; }
        .doc-modal-body ul, .doc-modal-body ol { padding-left: 20px; margin: 8px 0; }
        .doc-modal-body li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="doc-wrapper">
        <button class="doc-btn" id="docBtn" aria-label="Documentación">
            <span class="material-icons">menu</span>
        </button>
        <div class="doc-dropdown" id="docDropdown">
            <div class="doc-grid">
                <div class="doc-col">
                    <div class="doc-col-title">Acceso y Soporte</div>
                    <div class="doc-card" data-action="recuperar-acceso">
                        <span class="material-icons">lock_reset</span>
                        <span class="doc-card-text">
                            <span class="doc-card-title">Recuperar Acceso</span>
                            <span class="doc-card-desc">Guía para restablecer tu cuenta mediante tus 2/3 preguntas de seguridad del sistema.</span>
                        </span>
                    </div>
                    <div class="doc-card" data-action="dudas-comunes">
                        <span class="material-icons">help_outline</span>
                        <span class="doc-card-text">
                            <span class="doc-card-title">Dudas Comunes</span>
                            <span class="doc-card-desc">Preguntas frecuentes sobre el uso del dashboard de actividades.</span>
                        </span>
                    </div>
                </div>
                <div class="doc-col">
                    <div class="doc-col-title">Permisos del Sistema</div>
                    <div class="doc-card" data-action="super-usuario">
                        <span class="material-icons">admin_panel_settings</span>
                        <span class="doc-card-text">
                            <span class="doc-card-title">Super Usuario</span>
                            <span class="doc-card-desc">Perfil con acceso total al sistema: gestión de usuarios, roles, reportes y configuración general.</span>
                        </span>
                    </div>
                    <div class="doc-card" data-action="manual-usuario">
                        <span class="material-icons">description</span>
                        <span class="doc-card-text">
                            <span class="doc-card-title">Manual de Usuario</span>
                            <span class="doc-card-desc">Guía completa de usuario para el registro correcto de tareas.</span>
                        </span>
                    </div>
                </div>
                <div class="doc-col">
                    <div class="doc-col-title">Especificaciones</div>
                    <div class="doc-card" data-action="como-esta-hecho">
                        <span class="material-icons">code</span>
                        <span class="doc-card-text">
                            <span class="doc-card-title">Cómo está hecho</span>
                            <span class="doc-card-desc">Conoce el sistema base construido sobre arquitectura MVC en PHP nativo.</span>
                        </span>
                    </div>
                    <div class="doc-card" data-action="componentes-core">
                        <span class="material-icons">integration_instructions</span>
                        <span class="doc-card-text">
                            <span class="doc-card-title">Componentes Core</span>
                            <span class="doc-card-desc">Integración moderna con MySQL, SweetAlert2 y motor de reportes Dompdf.</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="doc-modal-overlay" id="docModalOverlay">
        <div class="doc-modal">
            <div class="doc-modal-header">
                <h3 id="docModalTitle"><span class="material-icons">info</span> Título</h3>
                <button class="doc-modal-close" id="docModalClose" aria-label="Cerrar">&times;</button>
            </div>
            <div class="doc-modal-body" id="docModalBody"></div>
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

    /* ── Documentación: toggle + modal flotante ── */
    (function() {
        var btn = document.getElementById('docBtn');
        var dd  = document.getElementById('docDropdown');
        var overlay = document.getElementById('docModalOverlay');
        var mTitle  = document.getElementById('docModalTitle');
        var mBody   = document.getElementById('docModalBody');
        var mClose  = document.getElementById('docModalClose');

        if (btn && dd) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                dd.classList.toggle('open');
            });
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.doc-wrapper')) dd.classList.remove('open');
            });
        }

        /* ── Contenido de cada sección ── */
        var secciones = {
            'recuperar-acceso': {
                icono: 'lock_reset',
                titulo: 'Recuperar Acceso',
                html: '<p style="color:#f2f3f5;font-weight:600;margin-bottom:10px;">Guía de restablecimiento de credenciales</p>' +
                      '<ol>' +
                      '<li><strong>Solicitud:</strong> El administrador inicia el proceso desde el panel de gestión de usuarios.</li>' +
                      '<li><strong>Verificación:</strong> El sistema te presentará <strong>2 de tus 3 preguntas de seguridad</strong> guardadas en tu perfil.</li>' +
                      '<li><strong>Respuestas:</strong> Debes contestar correctamente. Tras 3 intentos fallidos el proceso se bloquea por seguridad.</li>' +
                      '<li><strong>Nueva contraseña:</strong> Una vez verificada tu identidad, podrás establecer una nueva clave de acceso.</li>' +
                      '<li><strong>Inicio de sesión:</strong> Ingresa con tu nueva contraseña. Se recomienda actualizarla cada 90 días.</li>' +
                      '</ol>'
            },
            'dudas-comunes': {
                icono: 'help_outline',
                titulo: 'Dudas Comunes',
                html: '<p style="color:#f2f3f5;font-weight:600;margin-bottom:10px;">Preguntas frecuentes del sistema</p>' +
                      '<ul>' +
                      '<li><strong>¿Cómo registro una actividad?</strong> En el dashboard principal, haz clic en "Nueva Actividad". Completa los campos obligatorios y guarda.</li>' +
                      '<li><strong>¿Puedo editar una actividad finalizada?</strong> Sí, si tienes permisos. Usa el icono de lápiz en la fila correspondiente.</li>' +
                      '<li><strong>¿Qué es "En Progreso"?</strong> Es el estado inicial de una actividad recién creada que aún no se ha completado.</li>' +
                      '<li><strong>¿Cómo generar un reporte PDF?</strong> Ve a la sección Reportes, selecciona un rango de fechas y haz clic en "Generar PDF".</li>' +
                      '<li><strong>¿Quién puede ver la bitácora?</strong> Solo los usuarios con el permiso <em>bitacora</em> activado en su rol.</li>' +
                      '</ul>'
            },
            'super-usuario': {
                icono: 'admin_panel_settings',
                titulo: 'Super Usuario',
                html: '<p style="color:#f2f3f5;font-weight:600;margin-bottom:10px;">Perfil con acceso total al sistema</p>' +
                      '<p>El Super Usuario tiene control completo sobre todas las funcionalidades del sistema:</p>' +
                      '<ul>' +
                      '<li><strong>Gestión de usuarios:</strong> Crear, editar, desactivar y asignar roles a cualquier cuenta del sistema.</li>' +
                      '<li><strong>Asignación de roles:</strong> Puede otorgar los roles de Superadmin, Administrador o Usuario estándar.</li>' +
                      '<li><strong>Permisos granulares:</strong> Activa o restringe módulos como bitácora, reportes, usuarios o empleados para cada perfil.</li>' +
                      '<li><strong>Vistas personalizadas:</strong> Define qué secciones del dashboard son visibles para cada tipo de usuario.</li>' +
                      '<li><strong>Historial completo:</strong> Acceso a la bitácora del sistema con todas las acciones registradas.</li>' +
                      '</ul>'
            },
            'manual-usuario': {
                icono: 'description',
                titulo: 'Manual de Usuario',
                html: '<p style="color:#f2f3f5;font-weight:600;margin-bottom:10px;">Guía completa de uso</p>' +
                      '<ul>' +
                      '<li><strong>Registro de actividades:</strong> Creación, edición, finalización y cancelación de tareas del día a día.</li>' +
                      '<li><strong>Gestión de empleados:</strong> Alta, baja y modificación de datos del personal operativo.</li>' +
                      '<li><strong>Reportes PDF:</strong> Generación de documentos con filtros por fechas y áreas, usando el motor Dompdf.</li>' +
                      '<li><strong>Bitácora del sistema:</strong> Consulta el historial completo de acciones realizadas por todos los usuarios.</li>' +
                      '<li><strong>Seguridad:</strong> Las contraseñas se almacenan con hash y el acceso se controla mediante sesiones y permisos.</li>' +
                      '</ul>'
            },
            'como-esta-hecho': {
                icono: 'code',
                titulo: 'Cómo está hecho',
                html: '<p style="color:#f2f3f5;font-weight:600;margin-bottom:10px;">Arquitectura del sistema O.S.T.I</p>' +
                      '<ul>' +
                      '<li><strong>Frontend:</strong> HTML5 semántico con CSS3 vanilla, diseño responsivo y Material Icons de Google para la iconografía.</li>' +
                      '<li><strong>Backend:</strong> PHP 8.x nativo siguiendo el patrón MVC (Modelo-Vista-Controlador) sin dependencia de frameworks pesados.</li>' +
                      '<li><strong>Base de datos:</strong> MySQL con motor InnoDB, consultas parametrizadas (Prepared Statements) para prevenir inyección SQL.</li>' +
                      '<li><strong>Interactividad:</strong> SweetAlert2 para modales y notificaciones toast; JavaScript vanilla para el resto de la lógica cliente.</li>' +
                      '<li><strong>Reportes:</strong> Dompdf convierte HTML+CSS a PDF con soporte UTF-8 nativo, reemplazando al antiguo FPDF.</li>' +
                      '</ul>'
            },
            'componentes-core': {
                icono: 'integration_instructions',
                titulo: 'Componentes Core',
                html: '<p style="color:#f2f3f5;font-weight:600;margin-bottom:10px;">Stack tecnológico principal</p>' +
                      '<ul>' +
                      '<li><strong>PHP 8.x:</strong> Lenguaje del backend con tipado estricto y orientación a objetos.</li>' +
                      '<li><strong>MySQL + InnoDB:</strong> Motor de base de datos relacional con transacciones, foreign keys y integridad referencial.</li>' +
                      '<li><strong>SweetAlert2:</strong> Librería JavaScript para diálogos modales elegantes y notificaciones toast no obstructivas.</li>' +
                      '<li><strong>Dompdf 3.x:</strong> Librería PHP que renderiza HTML y CSS a PDF, con soporte completo de UTF-8 y fuentes incrustadas.</li>' +
                      '<li><strong>Material Icons:</strong> Set de iconos tipográficos de Google que escalan sin pérdida de calidad.</li>' +
                      '<li><strong>Servidor:</strong> XAMPP con Apache 2.4, desplegado en entorno Windows para desarrollo y producción local.</li>' +
                      '</ul>'
            }
        };

        /* ── Mostrar modal ── */
        function mostrarSeccion(action) {
            var s = secciones[action];
            if (!s) return;
            dd.classList.remove('open');
            mTitle.innerHTML = '<span class="material-icons">'+s.icono+'</span> '+s.titulo;
            mBody.innerHTML = s.html;
            overlay.classList.add('show');
        }

        /* ── Cerrar modal y regresar al menú ── */
        function cerrarModal() {
            overlay.classList.remove('show');
            dd.classList.add('open');
        }

        /* ── Evento en cada tarjeta ── */
        document.querySelectorAll('.doc-card').forEach(function(card) {
            card.addEventListener('click', function(e) {
                mostrarSeccion(this.getAttribute('data-action'));
            });
        });

        /* ── Cerrar con X o al hacer clic fuera ── */
        if (mClose) mClose.addEventListener('click', cerrarModal);
        if (overlay) overlay.addEventListener('click', function(e) {
            if (e.target === overlay) cerrarModal();
        });
    })();
    </script>
</body>
</html>