<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/permisos.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

date_default_timezone_set('America/Caracas');

if (!isset($_SESSION['user_id'])) {
    die('No autorizado');
}

if (!tienePermiso('reportes_pdf')) {
    die('No tienes permiso para generar reportes PDF');
}

$esValidacion = ($_GET['validar'] ?? '') === '1';

$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin    = $_GET['fecha_fin'] ?? '';

// ── Validación básica de presencia ──
if (!$fechaInicio || !$fechaFin) {
    $msg = 'Debe indicar un rango de fechas';
    if ($esValidacion) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'message' => $msg]);
        exit;
    }
    $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => $msg];
    header('Location: ../dashboard.php');
    exit;
}

// ── Parseo estricto con DateTime ──
try {
    $fInicio = new DateTime($fechaInicio);
    $fFin    = new DateTime($fechaFin);
} catch (Exception $e) {
    $msg = 'Formato de fecha inválido';
    if ($esValidacion) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'message' => $msg]);
        exit;
    }
    $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => $msg];
    header('Location: ../dashboard.php');
    exit;
}

// ── VALIDACIÓN A: coherencia de fechas ──
if ($fInicio > $fFin) {
    $msg = 'La fecha de inicio debe ser inferior a la fecha fin';
    if ($esValidacion) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'message' => $msg]);
        exit;
    }
    $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => $msg];
    header('Location: ../dashboard.php');
    exit;
}

// ── VALIDACIÓN B: límite mensual de 32 días ──
$intervalo = $fInicio->diff($fFin);
if ($intervalo->days > 32) {
    $msg = 'El rango seleccionado excede el límite permitido para reportes mensuales (Máximo 32 días)';
    if ($esValidacion) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'message' => $msg]);
        exit;
    }
    $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => $msg];
    header('Location: ../dashboard.php');
    exit;
}

// ── Validación: volumen mínimo ──
$sqlCount = "SELECT COUNT(*) AS total FROM actividades WHERE DATE(fecha_inicio) BETWEEN ? AND ?";
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bind_param('ss', $fechaInicio, $fechaFin);
$stmtCount->execute();
$totalActividades = (int) $stmtCount->get_result()->fetch_assoc()['total'];
$stmtCount->close();

if ($totalActividades < 10) {
    $msg = 'El rango de la actividad no alcanza el mínimo establecido';
    if ($esValidacion) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'message' => $msg]);
        exit;
    }
    $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => $msg];
    header('Location: ../dashboard.php');
    exit;
}

if ($esValidacion) {
    // Validar que ninguna actividad finalizada tenga fecha_inicio > fecha_fin
    $sqlCheck = "SELECT COUNT(*) AS anomalias FROM actividades WHERE estado = 'Finalizada' AND fecha_fin IS NOT NULL AND fecha_inicio > fecha_fin AND DATE(fecha_inicio) BETWEEN ? AND ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param('ss', $fechaInicio, $fechaFin);
    $stmtCheck->execute();
    $anomalias = (int) $stmtCheck->get_result()->fetch_assoc()['anomalias'];
    $stmtCheck->close();
    if ($anomalias > 0) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'message' => 'La fecha de inicio debe ser inferior a la fecha fin']);
        exit;
    }
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(['ok' => true]);
    exit;
}

// ── Consulta principal ──
$sql = "SELECT a.id, a.descripcion, a.area, a.estado, a.fecha_inicio, a.fecha_fin, a.fecha_limite, a.responsables_data,
               COALESCE(u.nombre_completo, u.username, CONCAT('ID: ', a.id_usuario)) AS usuario_registro
        FROM actividades a
        LEFT JOIN usuarios u ON u.id = a.id_usuario
        WHERE DATE(a.fecha_inicio) BETWEEN ? AND ?
        ORDER BY a.fecha_inicio ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $fechaInicio, $fechaFin);
$stmt->execute();
$res = $stmt->get_result();

// ── Construir HTML del reporte ──
$filasHtml = '';
$contador = 0;

while ($row = $res->fetch_assoc()) {
    $contador++;
    $id          = (int) $row['id'];
    $fecha       = date('d-m-Y', strtotime($row['fecha_inicio']));
    $area        = htmlspecialchars(mb_substr((string) $row['area'], 0, 50), ENT_QUOTES, 'UTF-8');
    $estado      = htmlspecialchars(mb_substr((string) $row['estado'], 0, 15), ENT_QUOTES, 'UTF-8');
    $descripcion = htmlspecialchars(mb_substr((string) ($row['descripcion'] ?? ''), 0, 150), ENT_QUOTES, 'UTF-8');

    // Duración: solo para finalizadas, de lo contrario em dash
    $esFinalizada = ($row['estado'] ?? '') === 'Finalizada';
    if ($esFinalizada && !empty($row['fecha_fin'])) {
        $duracionHtml = htmlspecialchars(formatearDuracion($row['fecha_inicio'], $row['fecha_fin']), ENT_QUOTES, 'UTF-8');
    } else {
        $duracionHtml = '&mdash;';
    }

    // Responsables
    $responsables = json_decode($row['responsables_data'] ?? '[]', true);
    $nombres = array_map(static function ($r) {
        return mb_substr($r['nombre'] ?? '', 0, 30);
    }, is_array($responsables) ? $responsables : []);
    $nombres = array_values(array_filter(array_map('trim', $nombres), static fn($v) => $v !== ''));
    $responsablesStr = implode(', ', $nombres);

    $filasHtml .= "<tr>
        <td class=\"celda-centrada\">{$id}</td>
        <td class=\"celda-fecha\">{$fecha}</td>
        <td class=\"celda-centrada\">{$area}</td>
        <td class=\"celda-centrada\">{$estado}</td>
        <td class=\"celda-centrada\">{$duracionHtml}</td>
        <td class=\"celda-centrada\">" . htmlspecialchars($responsablesStr, ENT_QUOTES, 'UTF-8') . "</td>
        <td class=\"celda-desc\">{$descripcion}</td>
    </tr>";
}

$usuarioGenerador = htmlspecialchars($_SESSION['nombre'] ?? 'Usuario', ENT_QUOTES, 'UTF-8');
$rangoTexto = htmlspecialchars("{$fechaInicio} al {$fechaFin}", ENT_QUOTES, 'UTF-8');
$fechaGeneracion = date('d-m-Y H:i');

$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 20mm 15mm; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; color: #202124; }
    h1 { font-size: 16pt; margin: 0 0 4px 0; color: #202124; }
    .meta { color: #5f6368; font-size: 9pt; margin: 0 0 2px 0; }
    .fecha-gen { color: #9aa0a6; font-size: 8pt; margin-top: 2px; }
    table { width: 100%; table-layout: fixed !important; border-collapse: collapse; margin-top: 12px; font-size: 8pt; }
    th { background: #f5f5f5; font-weight: 600; padding: 7px 4px; border: 1px solid #dadce0; text-align: center; }
    td { padding: 5px 4px; border: 1px solid #dadce0; word-wrap: break-word !important; word-break: break-all !important; overflow: hidden; }
    .celda-centrada { text-align: center; }
    .celda-fecha { text-align: center; white-space: nowrap !important; }
    .celda-desc { text-align: justify; }
    colgroup col:nth-child(1) { width: 5%; }
    colgroup col:nth-child(2) { width: 10%; }
    colgroup col:nth-child(3) { width: 15%; }
    colgroup col:nth-child(4) { width: 12%; }
    colgroup col:nth-child(5) { width: 12%; }
    colgroup col:nth-child(6) { width: 16%; }
    colgroup col:nth-child(7) { width: 30%; }
</style>
</head>
<body>
    <h1>Reporte de Actividades &mdash; Sistema O.S.T.I.</h1>
    <p class="meta">Rango: {$rangoTexto}</p>
    <p class="meta">Generado por: {$usuarioGenerador}</p>
    <p class="fecha-gen">Generado el: {$fechaGeneracion}</p>
    <table>
        <colgroup>
            <col style="width: 5%;">
            <col style="width: 10%;">
            <col style="width: 15%;">
            <col style="width: 12%;">
            <col style="width: 12%;">
            <col style="width: 16%;">
            <col style="width: 30%;">
        </colgroup>
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="10%">Fecha</th>
                <th width="15%">Área</th>
                <th width="12%">Estado</th>
                <th width="12%">Duración</th>
                <th width="16%">Responsables</th>
                <th width="30%">Descripción</th>
            </tr>
        </thead>
        <tbody>
            {$filasHtml}
        </tbody>
    </table>
</body>
</html>
HTML;

// ── Log de generación de reporte ──
$detalleReporte = json_encode([
    'fecha_inicio' => $fechaInicio,
    'fecha_fin'    => $fechaFin,
    'total'        => $contador,
], JSON_UNESCAPED_UNICODE);
registrar_log($conn, (int) $_SESSION['user_id'], 'Generó reporte PDF', $detalleReporte);

// ── Generar PDF con Dompdf ──
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('reporte_actividades.pdf', ['Attachment' => false]);
