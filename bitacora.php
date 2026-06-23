<?php
include 'includes/config.php';
require_once 'includes/functions.php';

if (!tienePermiso('bitacora')) {
    http_response_code(403);
    echo 'No tienes permiso para acceder a la Bitácora.';
    exit;
}

asegurarTablaLogs($conn);
asegurarColumnaDetalleLogs($conn);

$porPagina = 30;
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$offset = ($pagina - 1) * $porPagina;

$totalRes = $conn->query("SELECT COUNT(*) AS total FROM logs_sistema");
$totalFilas = (int) ($totalRes ? $totalRes->fetch_assoc()['total'] : 0);
$totalPaginas = max(1, (int) ceil($totalFilas / $porPagina));
if ($pagina > $totalPaginas) $pagina = $totalPaginas;

$sql = "SELECT l.id, l.accion, l.detalle, l.fecha, u.nombre_completo AS usuario_nombre
        FROM logs_sistema l
        LEFT JOIN usuarios u ON l.usuario_id = u.id
        ORDER BY l.id DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $porPagina, $offset);
$stmt->execute();
$logs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$filtro_sidebar = 'todos';

include 'includes/header.php';
include 'includes/sidebar.php';
?>
<style>
.main-content table { table-layout: fixed; }
.main-content table th:nth-child(1) { width:15%; }
.main-content table th:nth-child(2) { width:70%; }
.main-content table th:nth-child(3) { width:15%; text-align:right; }
.ver-info-badge { display:inline-flex; align-items:center; margin-left:6px; padding:2px 8px; border-radius:12px; background:#e8f0fe; color:#1a73e8; font-size:12px; font-weight:500; text-decoration:none; white-space:nowrap; vertical-align:middle; cursor:pointer; transition:background 0.15s; }
.ver-info-badge:hover { background:#d2e3fc; }
</style>
<main class="main-content" style="flex:1; display:flex; flex-direction:column; padding:24px 28px; overflow-y:auto; min-width:0;">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
        <span class="material-icons" style="font-size:28px; color:#1a73e8;">history</span>
        <h1 style="font-size:22px; font-weight:600; color:#3c4043; margin:0;">Bitácora del Sistema</h1>
        <span style="margin-left:auto; font-size:13px; color:#5f6368; background:#f1f3f4; padding:6px 14px; border-radius:16px;">
            <?php echo $totalFilas; ?> registro<?php echo $totalFilas !== 1 ? 's' : ''; ?>
        </span>
        <button onclick="vaciarHistorial()" style="background:none; border:1px solid #d93025; color:#d93025; padding:6px 16px; border-radius:20px; cursor:pointer; font-size:13px; font-weight:500; transition:all 0.2s; white-space:nowrap;" onmouseover="this.style.background='#fce8e6'" onmouseout="this.style.background='none'">
            <span class="material-icons" style="font-size:16px; vertical-align:middle; margin-right:4px;">delete_sweep</span> Vaciar Historial
        </button>
    </div>

    <div class="tabla-container" style="flex:1; border:1px solid #e0e0e0; border-radius:12px; overflow-y:auto; overflow-x:hidden; max-height:calc(100vh - 260px); box-shadow:0 1px 4px rgba(60,64,67,0.08); background:#fff;">
        <table style="width:100%; border-collapse:collapse;">
            <thead style="position:sticky; top:0; z-index:2; background:#f8f9fa;">
                <tr style="background:#f8f9fa; border-bottom:2px solid #e0e0e0;">
                    <th style="padding:14px 18px; text-align:left; font-size:13px; font-weight:600; color:#5f6368; text-transform:uppercase; letter-spacing:0.5px;">Usuario</th>
                    <th style="padding:14px 18px; text-align:left; font-size:13px; font-weight:600; color:#5f6368; text-transform:uppercase; letter-spacing:0.5px;">Acción Realizada</th>
                    <th style="padding:14px 18px; text-align:right; font-size:13px; font-weight:600; color:#5f6368; text-transform:uppercase; letter-spacing:0.5px;">Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="3" style="padding:48px 18px; text-align:center; color:#9aa0a6; font-size:14px;">
                            <span class="material-icons" style="font-size:40px; display:block; margin-bottom:12px; color:#dadce0;">history</span>
                            No hay registros en la bitácora.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr style="border-bottom:1px solid #f1f3f4; transition:background 0.15s;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background=''">
                            <td style="padding:12px 18px; font-size:14px; color:#3c4043; font-weight:500;">
                                <?php echo htmlspecialchars($log['usuario_nombre'] ?? 'Usuario eliminado', ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td style="padding:12px 18px; font-size:14px; color:#5f6368;">
                                <?php
                                $tieneDetalle = !empty($log['detalle']);
                                $textoAccion = $log['accion'];
                                if ($tieneDetalle):
                                    $textoMostrar = preg_replace('/\.\.\.\s*\(Ver info\)$/', '', $textoAccion);
                                    echo htmlspecialchars($textoMostrar, ENT_QUOTES, 'UTF-8');
                                ?>
                                <a href="javascript:void(0)" onclick="verDetalleLog(<?php echo (int) $log['id']; ?>)" class="ver-info-badge">(Ver info)</a>
                                <?php else: ?>
                                <?php echo htmlspecialchars($textoAccion, ENT_QUOTES, 'UTF-8'); ?>
                                <?php endif; ?>
                            </td>
                            <td style="padding:12px 18px; font-size:13px; color:#9aa0a6; text-align:right; white-space:nowrap;">
                                <?php
                                $fechaTS = strtotime($log['fecha']);
                                echo $fechaTS ? date('d-m-Y', $fechaTS) : htmlspecialchars($log['fecha']);
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPaginas > 1): ?>
    <div style="display:flex; align-items:center; justify-content:center; gap:6px; margin-top:20px; padding:8px 0;">
        <?php if ($pagina > 1): ?>
            <a href="bitacora.php?pagina=<?php echo $pagina - 1; ?>" style="display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid #dadce0; color:#5f6368; text-decoration:none; font-size:14px; transition:0.15s; background:#fff;" onmouseover="this.style.background='#f1f3f4'" onmouseout="this.style.background='#fff'">
                <span class="material-icons" style="font-size:18px;">chevron_left</span>
            </a>
        <?php endif; ?>

        <?php
        $inicio = max(1, $pagina - 2);
        $fin = min($totalPaginas, $pagina + 2);
        for ($i = $inicio; $i <= $fin; $i++):
        ?>
            <a href="bitacora.php?pagina=<?php echo $i; ?>"
               style="display:flex; align-items:center; justify-content:center; min-width:36px; height:36px; border-radius:8px; border:1px solid <?php echo $i === $pagina ? '#1a73e8' : '#dadce0'; ?>; color:<?php echo $i === $pagina ? '#fff' : '#5f6368'; ?>; text-decoration:none; font-size:13px; font-weight:<?php echo $i === $pagina ? '600' : '400'; ?>; background:<?php echo $i === $pagina ? '#1a73e8' : '#fff'; ?>; transition:0.15s;"
               onmouseover="this.style.background='<?php echo $i === $pagina ? '#1557b0' : '#f1f3f4'; ?>'" onmouseout="this.style.background='<?php echo $i === $pagina ? '#1a73e8' : '#fff'; ?>'">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($pagina < $totalPaginas): ?>
            <a href="bitacora.php?pagina=<?php echo $pagina + 1; ?>" style="display:flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:8px; border:1px solid #dadce0; color:#5f6368; text-decoration:none; font-size:14px; transition:0.15s; background:#fff;" onmouseover="this.style.background='#f1f3f4'" onmouseout="this.style.background='#fff'">
                <span class="material-icons" style="font-size:18px;">chevron_right</span>
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</main>

<div id="modalDetalleLog" class="modal" style="z-index:2000;">
    <div class="modal-content" style="max-width:560px;">
        <span class="material-icons btn-close-modal" onclick="cerrarModalDetalleLog()">close</span>
        <h2 style="display:flex; align-items:center; gap:10px; margin-bottom:16px; color:#3c4043;">
            <span class="material-icons">info</span> Detalle de la Acción
        </h2>
        <div id="detalleLogContenido" style="background:#f8f9fa; border:1px solid #dadce0; border-radius:8px; padding:16px; font-size:14px; line-height:1.6; white-space:pre-wrap; max-height:400px; overflow-y:auto;"></div>
        <div style="margin-top:20px; display:flex; justify-content:flex-end;">
            <button type="button" class="btn-cancel" onclick="cerrarModalDetalleLog()" style="padding:8px 24px;">Cerrar</button>
        </div>
    </div>
</div>

<script>
function cerrarModalDetalleLog() {
    var modal = document.getElementById('modalDetalleLog');
    if (modal) modal.style.display = 'none';
}

function verDetalleLog(logId) {
    if (!logId) return;
    var detalle = <?php
        $detallesMap = [];
        foreach ($logs as $log) {
            if (!empty($log['detalle'])) {
                $detallesMap[(int) $log['id']] = $log['detalle'];
            }
        }
        echo json_encode($detallesMap, JSON_UNESCAPED_UNICODE);
    ?>;
    var raw = detalle[logId];
    if (!raw) {
        Swal.fire({
            icon: 'error', title: 'No hay detalle disponible',
            confirmButtonText: 'Aceptar',
            customClass: { popup: 'osti-swal', confirmButton: 'osti-btn' },
            buttonsStyling: false
        });
        return;
    }
    var contenido = '';
    try {
        var obj = JSON.parse(raw);
        if (obj.tipo === 'actividad' && Array.isArray(obj.cambios)) {
            contenido = '<strong>Cambios realizados:</strong><br><ul style="margin:8px 0 0 16px; padding:0;">';
            obj.cambios.forEach(function(c) {
                contenido += '<li style="margin-bottom:4px;">' + c + '</li>';
            });
            contenido += '</ul>';
            if (obj.actividad_id) {
                contenido += '<br><div style="color:#5f6368; font-size:13px;">Actividad ID: <strong>' + obj.actividad_id + '</strong></div>';
            }
        } else if (obj.tipo === 'actividad' && obj.accion === 'creacion') {
            contenido = '<strong>Actividad creada</strong><br>';
            contenido += '<div style="margin-top:8px;">Descripción: ' + (obj.descripcion || 'N/D') + '</div>';
            contenido += '<div>Área: ' + (obj.area || 'N/D') + '</div>';
            contenido += '<div>Estado: ' + (obj.estado || 'N/D') + '</div>';
            if (obj.actividad_id) {
                contenido += '<br><div style="color:#5f6368; font-size:13px;">Actividad ID: <strong>' + obj.actividad_id + '</strong></div>';
            }
        } else if (obj.tipo === 'actividad' && obj.accion === 'finalizacion') {
            contenido = '<strong>Actividad finalizada</strong><br>';
            contenido += '<div style="margin-top:8px;">Fecha de finalización: ' + (obj.fecha_fin || 'N/D') + '</div>';
            if (obj.actividad_id) {
                contenido += '<div style="color:#5f6368; font-size:13px; margin-top:4px;">Actividad ID: <strong>' + obj.actividad_id + '</strong></div>';
            }
        } else if (obj.tipo === 'actividad' && obj.accion === 'eliminacion') {
            contenido = '<strong>Actividad eliminada</strong><br>';
            contenido += '<div style="margin-top:8px;">Descripción: ' + (obj.descripcion || 'N/D') + '</div>';
            contenido += '<div>Área: ' + (obj.area || 'N/D') + '</div>';
            if (obj.actividad_id) {
                contenido += '<div style="color:#d93025; margin-top:4px;">Actividad ID: <strong>' + obj.actividad_id + '</strong></div>';
            }
        } else if (obj.tipo === 'usuario') {
            contenido = '<strong>Usuario:</strong> ' + (obj.nombre || obj.nombre_completo || 'N/D') + '<br>';
            if (obj.username) contenido += '<div>Username: ' + obj.username + '</div>';
            if (obj.formacion) contenido += '<div>Formación: ' + obj.formacion + '</div>';
            if (obj.correo) contenido += '<div>Correo: ' + obj.correo + '</div>';
            if (obj.telefono) contenido += '<div>Teléfono: ' + obj.telefono + '</div>';
            if (obj.accion === 'eliminacion' && obj.usuario_id) {
                contenido += '<div style="color:#d93025; margin-top:4px;">Usuario ID: <strong>' + obj.usuario_id + '</strong></div>';
            }
        } else if (obj.tipo === 'empleado') {
            contenido = '<strong>Empleado:</strong> ' + (obj.nombre || '') + ' ' + (obj.apellido || '') + '<br>';
            if (obj.formacion) contenido += '<div>Formación: ' + obj.formacion + '</div>';
            if (obj.correo) contenido += '<div>Correo: ' + obj.correo + '</div>';
            if (obj.telefono) contenido += '<div>Teléfono: ' + obj.telefono + '</div>';
        } else {
            contenido = '<pre style="margin:0; font-size:13px;">' + JSON.stringify(obj, null, 2) + '</pre>';
        }
    } catch(e) {
        contenido = '<pre style="margin:0; font-size:13px;">' + raw + '</pre>';
    }
    document.getElementById('detalleLogContenido').innerHTML = contenido;
    document.getElementById('modalDetalleLog').style.display = 'block';
}

function vaciarHistorial() {
    Swal.fire({
        title: '¿Vaciar historial?',
        text: 'Esta acción eliminará permanentemente todos los logs del sistema.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d93025',
        cancelButtonColor: '#5f6368',
        confirmButtonText: 'Sí, vaciar todo',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then(function(result) {
        if (!result.isConfirmed) return;
        fetch('includes/vaciar_logs.php', { method: 'POST' })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.ok) {
                    location.reload();
                } else {
                    mostrarToastPersonalizado(data.message || 'Error al vaciar el historial', 'error');
                }
            })
            .catch(function() {
                mostrarToastPersonalizado('Error de red', 'error');
            });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var modalDetalle = document.getElementById('modalDetalleLog');
    if (modalDetalle) {
        modalDetalle.addEventListener('click', function(e) {
            if (e.target === modalDetalle) cerrarModalDetalleLog();
        });
    }
});
</script>

<?php include 'includes/footer.php'; ?>
