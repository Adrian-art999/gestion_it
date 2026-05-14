<?php
include 'includes/config.php';
include 'includes/db_schema.php';
require_once 'includes/functions.php';
asegurarTablaHistorialActividades($conn);
asegurarEstadoActividades($conn);
normalizarEstadosActividades($conn);

$filtro_sidebar = $_GET['estado'] ?? 'todos';
$filtros_validos = ['todos', 'en_progreso', 'finalizadas', 'canceladas'];
if (!in_array($filtro_sidebar, $filtros_validos, true)) {
    $filtro_sidebar = 'todos';
}

$sqlConteoEstados = "SELECT
                        COUNT(*) AS todas,
                        SUM(CASE WHEN estado = 'En progreso' THEN 1 ELSE 0 END) AS en_progreso,
                        SUM(CASE WHEN estado = 'Finalizada' THEN 1 ELSE 0 END) AS finalizadas,
                        SUM(CASE WHEN estado = 'Cancelada' THEN 1 ELSE 0 END) AS canceladas
                    FROM actividades";
$resConteoEstados = $conn->query($sqlConteoEstados);
$filaConteoEstados = $resConteoEstados ? $resConteoEstados->fetch_assoc() : [];
$conteo_en_progreso = (int) ($filaConteoEstados['en_progreso'] ?? 0);
$conteo_finalizadas = (int) ($filaConteoEstados['finalizadas'] ?? 0);
$conteo_canceladas = (int) ($filaConteoEstados['canceladas'] ?? 0);
$conteo_todas = (int) ($filaConteoEstados['todas'] ?? 0);

$por_pagina = 25;
$pagina_actual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($pagina_actual - 1) * $por_pagina;

$where = '';
$ahora = date('Y-m-d 00:00:00');
if ($filtro_sidebar === 'en_progreso') {
    $where = " WHERE estado = 'En progreso' AND (fecha_limite IS NULL OR fecha_limite > '{$ahora}')";
} elseif ($filtro_sidebar === 'finalizadas') {
    $where = " WHERE estado = 'Finalizada'";
} elseif ($filtro_sidebar === 'canceladas') {
    $where = " WHERE estado = 'Cancelada'";
}

$stmtTotal = $conn->prepare("SELECT COUNT(*) AS total FROM actividades{$where}");
$stmtTotal->execute();
$fila_total = $stmtTotal->get_result()->fetch_assoc();
$total_actividades = (int) ($fila_total['total'] ?? 0);
$total_paginas = max(1, (int) ceil($total_actividades / $por_pagina));

if ($pagina_actual > $total_paginas) {
    $pagina_actual = $total_paginas;
    $offset = ($pagina_actual - 1) * $por_pagina;
}

$sql = "SELECT a.*,
               COALESCE(u.nombre_completo, u.username, CONCAT('ID: ', a.id_usuario)) AS usuario_registro
        FROM actividades a
        LEFT JOIN usuarios u ON u.id = a.id_usuario
        {$where}
        ORDER BY a.id DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $por_pagina, $offset);
$stmt->execute();
$resultado_actividades = $stmt->get_result();

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="dashboard-top-actions">
        <button onclick="abrirModal('modalReportePDF')" class="btn-reporte-pdf">
            <span class="material-icons" style="font-size: 18px;">picture_as_pdf</span> Reporte PDF
        </button>
    </div>
    <div class="dashboard-greeting">
        Hola, <strong><?php echo htmlspecialchars($nombre_usuario ?: ($_SESSION['nombre'] ?? 'Usuario')); ?></strong>
    </div>

    <div class="toolbar dashboard-toolbar">
        <div class="search-container dashboard-search">
            <span class="material-icons">search</span>
            <input type="text" id="buscador" oninput="filtrarTabla()" placeholder="Buscar actividad..." 
                   class="dashboard-search-input">
        </div>
        <?php
        $estado_select = 'todos';
        if ($filtro_sidebar === 'en_progreso') {
            $estado_select = 'En progreso';
        } elseif ($filtro_sidebar === 'finalizadas') {
            $estado_select = 'Finalizada';
        } elseif ($filtro_sidebar === 'canceladas') {
            $estado_select = 'Cancelada';
        }
        ?>
        <select id="filtroEstado" class="filter-select dashboard-filter-select" onchange="filtrarTabla()">
            <option value="todos" <?php echo $estado_select === 'todos' ? 'selected' : ''; ?>>Todos los estados</option>
            <option value="En progreso" <?php echo $estado_select === 'En progreso' ? 'selected' : ''; ?>>En progreso</option>
            <option value="Finalizada" <?php echo $estado_select === 'Finalizada' ? 'selected' : ''; ?>>Finalizada</option>
            <option value="Cancelada" <?php echo $estado_select === 'Cancelada' ? 'selected' : ''; ?>>Cancelada</option>
        </select>
    </div>

    <div class="tabla-container">
        <table id="miTabla">
            <thead>
                <tr>
                    <th>Responsable(s)</th>
                    <th>Área</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th style="text-align: right;">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($resultado_actividades) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($resultado_actividades)): 
                        $responsables = json_decode($row['responsables_data'] ?? '[]', true);
                        $primer = $responsables[0]['nombre'] ?? 'Sin asignar';
                        $total = count($responsables);
                        $estado = $row['estado'] ?: 'En progreso';
                        $clase_estado = 'status-progreso';
                        if ($estado === 'Finalizada') {
                            $clase_estado = 'status-finalizada';
                        } elseif ($estado === 'Cancelada') {
                            $clase_estado = 'status-cancelada';
                        }
                        $descripcion = (string) ($row['descripcion'] ?? '');
                        $area = (string) ($row['area'] ?? '');
                        $area_corta = mb_strlen($area) > 28 ? mb_substr($area, 0, 28) . '...' : $area;
                        $area_completa = htmlspecialchars($area, ENT_QUOTES, 'UTF-8');
                        $descripcion_corta = mb_strlen($descripcion) > 55 ? mb_substr($descripcion, 0, 55) . '...' : $descripcion;
                        $descripcion_completa = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');
                        $usuario_registro = htmlspecialchars($row['usuario_registro'], ENT_QUOTES, 'UTF-8');
                        $fecha_registro = !empty($row['fecha_inicio']) ? date('d-m-Y', strtotime($row['fecha_inicio'])) : 'N/D';
                        $responsables_full = array_map(static function($r) {
                            return $r['nombre'] ?? 'Sin nombre';
                        }, $responsables);
                        $responsables_json_attr = htmlspecialchars(json_encode($responsables_full, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                    ?>
                        <tr data-estado="<?php echo htmlspecialchars($estado); ?>" data-row-actividad="<?php echo (int) $row['id']; ?>">
                            <td class="responsables-cell">
                                <?php echo htmlspecialchars($primer); ?>
                                <?php if($total > 1): ?>
                                    <button type="button" class="btn-responsables-toggle" data-responsables='<?php echo $responsables_json_attr; ?>' style="margin-left:8px;">
                                        (+<?php echo $total - 1; ?>)
                                    </button>
                                <?php endif; ?>
                                <div class="responsables-popover"></div>
                            </td>
                            <td class="cell-area">
                                <span class="cell-truncate" title="<?php echo $area_completa; ?>"><?php echo htmlspecialchars($area_corta); ?></span>
                                <?php if (mb_strlen($area) > 28): ?>
                                    <button type="button" class="btn-descripcion-expandir" data-descripcion="<?php echo $area_completa; ?>">+</button>
                                <?php endif; ?>
                            </td>
                            <td class="cell-description">
                                <span class="cell-truncate" title="<?php echo $descripcion_completa; ?>"><?php echo htmlspecialchars($descripcion_corta); ?></span>
                                <?php if (mb_strlen($descripcion) > 55): ?>
                                    <button type="button" class="btn-descripcion-expandir" data-descripcion="<?php echo $descripcion_completa; ?>">+</button>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-pill <?php echo $clase_estado; ?>">
                                    <?php echo htmlspecialchars($estado); ?>
                                </span>
                            </td>
                            <td style="text-align: right; overflow: visible;">
                                <div class="opciones-menu">
                                    <span class="material-icons btn-opciones" data-act-id="<?php echo (int) $row['id']; ?>" role="button" tabindex="0" aria-label="Abrir opciones de actividad" aria-expanded="false">more_vert</span>
                                    <div id="menu-actividad-<?php echo (int) $row['id']; ?>" class="dropdown-opciones">
                                        <?php if ($estado === 'En progreso'): ?>
                                            <button type="button" class="btn-accion-actividad-finalizar" data-act-id="<?php echo (int) $row['id']; ?>">
                                                <span class="material-icons">check</span> Finalizar Actividad
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn-accion-actividad-editar" data-act-id="<?php echo (int) $row['id']; ?>">
                                            <span class="material-icons">edit</span> Editar
                                        </button>
                                        <button type="button" class="btn-accion-actividad-eliminar" data-act-id="<?php echo (int) $row['id']; ?>" style="color:#d93025;">
                                            <span class="material-icons" style="color:#d93025;">delete</span> Eliminar
                                        </button>
                                        <button type="button" class="btn-accion-actividad-info"
                                                data-act-id="<?php echo (int) $row['id']; ?>"
                                                data-fecha-registro="<?php echo htmlspecialchars($fecha_registro, ENT_QUOTES, 'UTF-8'); ?>"
                                                data-usuario-registro="<?php echo $usuario_registro; ?>"
                                                data-descripcion="<?php echo $descripcion_completa; ?>">
                                            <span class="material-icons">info</span> Info
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <tr id="sinResultadosActividades" style="display:none;">
                        <td colspan="5" class="mensaje-vacio">No hay resultados</td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="mensaje-vacio">
                            <span class="material-icons" style="font-size: 48px; display: block; margin-bottom: 10px; color: #e8eaed;">inventory_2</span>
                            <?php if ($conteo_todas === 0): ?>
                                <div style="font-size: 16px; font-weight: 500;">No hay actividades registradas</div>
                                <div style="font-size: 13px; margin-top: 5px;">Las nuevas tareas que asignes aparecerán aquí.</div>
                            <?php else: ?>
                                <div style="font-size: 16px; font-weight: 500;">No hay actividades con este estado</div>
                                <div style="font-size: 13px; margin-top: 5px;">Prueba con otro filtro o vuelve a "Todas las actividades".</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="paginacion-actividades" aria-label="Paginación de actividades">
        <?php for ($p = 1; $p <= $total_paginas; $p++): ?>
            <a href="dashboard.php?estado=<?php echo urlencode($filtro_sidebar); ?>&pagina=<?php echo $p; ?>" class="pagina-cuadro <?php echo ($p === $pagina_actual) ? 'activa' : ''; ?>">
                <?php echo $p; ?>
            </a>
        <?php endfor; ?>
    </div>
</main>
<div style="height: 100px;"></div>

<?php include 'includes/footer.php'; ?>