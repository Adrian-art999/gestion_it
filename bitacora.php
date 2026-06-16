<?php
include 'includes/config.php';
require_once 'includes/functions.php';

if (!tienePermiso('bitacora')) {
    http_response_code(403);
    echo 'No tienes permiso para acceder a la Bitácora.';
    exit;
}

asegurarTablaLogs($conn);

$porPagina = 25;
$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$offset = ($pagina - 1) * $porPagina;

$totalRes = $conn->query("SELECT COUNT(*) AS total FROM logs_sistema");
$totalFilas = (int) ($totalRes ? $totalRes->fetch_assoc()['total'] : 0);
$totalPaginas = max(1, (int) ceil($totalFilas / $porPagina));
if ($pagina > $totalPaginas) $pagina = $totalPaginas;

$sql = "SELECT l.id, l.accion, l.fecha, u.nombre_completo AS usuario_nombre
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
.main-content table th:nth-child(1) { width: 180px; }
.main-content table th:nth-child(2) { }
.main-content table th:nth-child(3) { width: 100px; text-align: right; }
.main-content table th:nth-child(4) { width: 60px; text-align: right; }
.main-content table td:nth-child(2) { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 0; }
.main-content table td:nth-child(3) { white-space: nowrap; }
.action-cell { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 0; }
</style>
<main class="main-content" style="flex:1; display:flex; flex-direction:column; padding:24px 28px; overflow-y:auto; min-width:0;">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
        <span class="material-icons" style="font-size:28px; color:#1a73e8;">history</span>
        <h1 style="font-size:22px; font-weight:600; color:#3c4043; margin:0;">Bitácora del Sistema</h1>
        <span style="margin-left:auto; font-size:13px; color:#5f6368; background:#f1f3f4; padding:6px 14px; border-radius:16px;">
            <?php echo $totalFilas; ?> registro<?php echo $totalFilas !== 1 ? 's' : ''; ?>
        </span>
    </div>

    <div class="tabla-container" style="flex:1; border:1px solid #e0e0e0; border-radius:12px; overflow:hidden; box-shadow:0 1px 4px rgba(60,64,67,0.08); background:#fff;">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8f9fa; border-bottom:2px solid #e0e0e0;">
                    <th style="padding:14px 18px; text-align:left; font-size:13px; font-weight:600; color:#5f6368; text-transform:uppercase; letter-spacing:0.5px;">Usuario</th>
                    <th style="padding:14px 18px; text-align:left; font-size:13px; font-weight:600; color:#5f6368; text-transform:uppercase; letter-spacing:0.5px;">Acción Realizada</th>
                    <th style="padding:14px 18px; text-align:right; font-size:13px; font-weight:600; color:#5f6368; text-transform:uppercase; letter-spacing:0.5px;">Fecha</th>
                    <th style="padding:14px 18px; text-align:right; font-size:13px; font-weight:600; color:#5f6368; text-transform:uppercase; letter-spacing:0.5px;">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="4" style="padding:48px 18px; text-align:center; color:#9aa0a6; font-size:14px;">
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
                            <td style="padding:12px 18px; font-size:14px; color:#5f6368;" class="action-cell" title="<?php echo htmlspecialchars($log['accion'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($log['accion'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>
                            <td style="padding:12px 18px; font-size:13px; color:#9aa0a6; text-align:right; white-space:nowrap;">
                                <?php
                                $fechaTS = strtotime($log['fecha']);
                                echo $fechaTS ? date('d-m-Y', $fechaTS) : htmlspecialchars($log['fecha']);
                                ?>
                            </td>
                            <td style="padding:12px 18px; text-align:right; overflow:visible; position:relative;">
                                <div class="opciones-menu">
                                    <span class="material-icons btn-opciones btn-bitacora-opciones" role="button" tabindex="0" aria-label="Abrir opciones" aria-expanded="false" data-log-id="<?php echo (int) $log['id']; ?>">more_vert</span>
                                    <div class="dropdown-opciones dropdown-bitacora">
                                        <button type="button" class="btn-eliminar-log" data-log-id="<?php echo (int) $log['id']; ?>" style="color:#d93025;">
                                            <span class="material-icons" style="color:#d93025;">delete</span> Eliminar
                                        </button>
                                    </div>
                                </div>
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

<script>
function inicializarDropdownsBitacora() {
    document.querySelectorAll('.btn-bitacora-opciones').forEach(function(btn) {
        btn.onclick = function(e) {
            e.stopPropagation();
            var menu = this.parentElement.querySelector('.dropdown-opciones');
            if (!menu) return;
            var isOpen = menu.classList.contains('show');

            document.querySelectorAll('.dropdown-bitacora.show').forEach(function(m) {
                m.classList.remove('show');
                m.style.position = '';
                m.style.top = '';
                m.style.right = '';
            });

            if (!isOpen) {
                var rect = this.getBoundingClientRect();
                menu.style.position = 'fixed';
                menu.style.top = rect.bottom + 'px';
                menu.style.right = (document.documentElement.clientWidth - rect.right) + 'px';
                menu.classList.add('show');
                this.setAttribute('aria-expanded', 'true');
            } else {
                this.setAttribute('aria-expanded', 'false');
            }
        };
    });
}

function inicializarBotonesEliminarLog() {
    document.querySelectorAll('.btn-eliminar-log').forEach(function(btn) {
        btn.onclick = function(e) {
            e.stopPropagation();
            var logId = this.dataset.logId;
            if (!logId) return;

            Swal.fire({
                title: '¿Estás seguro?',
                text: '¿Estás seguro de eliminar este registro de la bitácora?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d93025',
                cancelButtonColor: '#5f6368',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then(function(result) {
                if (!result.isConfirmed) return;

                var formData = new FormData();
                formData.append('id', logId);

                fetch('includes/eliminar_log.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.ok) {
                        var row = btn.closest('tr');
                        if (row) row.remove();
                        mostrarToastPersonalizado('Registro eliminado correctamente', 'success');
                    } else {
                        mostrarToastPersonalizado(data.message || 'Error al eliminar', 'error');
                    }
                })
                .catch(function() {
                    mostrarToastPersonalizado('Error de red', 'error');
                });
            });
        };
    });
}

document.addEventListener('DOMContentLoaded', function() {
    inicializarDropdownsBitacora();
    inicializarBotonesEliminarLog();

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.btn-bitacora-opciones')) {
            document.querySelectorAll('.dropdown-bitacora.show').forEach(function(m) {
                m.classList.remove('show');
                m.style.position = '';
                m.style.top = '';
                m.style.right = '';
            });
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
