<?php
session_start();
require_once '../includes/db.php';
<<<<<<< HEAD
require_once '../includes/permisos.php';
require_once '../includes/functions.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
define('FPDF_FONTPATH', dirname(__DIR__) . '/libs/fpdf/font/');
require_once '../libs/fpdf/fpdf.php';
date_default_timezone_set('America/Caracas');

if (!isset($_SESSION['user_id'])) {
    die('No autorizado');
}

<<<<<<< HEAD
if (!tienePermiso('reportes_pdf')) {
    die('No tienes permiso para generar reportes PDF');
}

$esValidacion = ($_GET['validar'] ?? '') === '1';

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin = $_GET['fecha_fin'] ?? '';

if (!$fechaInicio || !$fechaFin) {
<<<<<<< HEAD
    if ($esValidacion) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'message' => 'Debe indicar un rango de fechas']);
        exit;
    }
    $_SESSION['toast'] = ['tipo' => 'error', 'mensaje' => 'Debe indicar un rango de fechas'];
    header('Location: ../dashboard.php');
    exit;
}

// ── Validación 1: volumen mínimo ──
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

$sql = "SELECT a.id, a.descripcion, a.area, a.estado, a.fecha_inicio, a.fecha_fin, a.fecha_limite, a.responsables_data,
=======
    die('Debe indicar rango de fechas');
}

$sql = "SELECT a.id, a.descripcion, a.area, a.estado, a.fecha_inicio, a.fecha_limite, a.responsables_data,
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
               COALESCE(u.nombre_completo, u.username, CONCAT('ID: ', a.id_usuario)) AS usuario_registro
        FROM actividades a
        LEFT JOIN usuarios u ON u.id = a.id_usuario
        WHERE DATE(a.fecha_inicio) BETWEEN ? AND ?
        ORDER BY a.fecha_inicio ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $fechaInicio, $fechaFin);
$stmt->execute();
$res = $stmt->get_result();

<<<<<<< HEAD
=======
$totalActividades = $res ? $res->num_rows : 0;
if ($totalActividades < 10) {
    header('Content-Type: text/html; charset=UTF-8');
    echo "<!doctype html><html lang='es'><head><meta charset='UTF-8'><title>Reporte no disponible</title></head><body style='font-family:Segoe UI,Arial,sans-serif;padding:24px;color:#3c4043;'>
            <h3 style='margin:0 0 10px 0;'>Reporte no generado</h3>
            <p style='margin:0;'>Se requieren al menos 10 actividades en el rango seleccionado para generar el reporte mensual. Actualmente hay {$totalActividades}.</p>
          </body></html>";
    exit;
}

>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetTitle('Reporte Mensual de Actividades');
$pdf->SetAuthor('Sistema O.S.T.I.');
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(32, 33, 36);
$pdf->Cell(0, 10, utf8_decode('Reporte de Actividades - Sistema O.S.T.I.'), 0, 1, 'L');

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(95, 99, 104);
$pdf->Cell(0, 7, utf8_decode("Rango: {$fechaInicio} al {$fechaFin}"), 0, 1, 'L');
$pdf->Cell(0, 7, utf8_decode('Generado por: ' . ($_SESSION['nombre'] ?? 'Usuario')), 0, 1, 'L');
$pdf->Ln(2);

<<<<<<< HEAD
// Anchos proporcionales (total = 190mm para A4 con márgenes de 10mm)
$wId       = 10;
$wFecha    = 20;
$wArea     = 22;
$wEstado   = 18;
$wDuracion = 22;
$wResp     = 36;
$wDesc     = 62;

$pdf->SetFillColor(232, 240, 254);
$pdf->SetTextColor(32, 33, 36);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($wId, 8, '#', 1, 0, 'C', true);
$pdf->Cell($wFecha, 8, 'Fecha', 1, 0, 'C', true);
$pdf->Cell($wArea, 8, utf8_decode('Área'), 1, 0, 'C', true);
$pdf->Cell($wEstado, 8, 'Estado', 1, 0, 'C', true);
$pdf->Cell($wDuracion, 8, utf8_decode('Duración'), 1, 0, 'C', true);
$pdf->Cell($wResp, 8, 'Responsables', 1, 0, 'C', true);
$pdf->Cell($wDesc, 8, utf8_decode('Descripción'), 1, 1, 'C', true);
=======
$pdf->SetFillColor(232, 240, 254);
$pdf->SetTextColor(32, 33, 36);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(12, 8, '#', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Fecha', 1, 0, 'C', true);
$pdf->Cell(28, 8, utf8_decode('Área'), 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Estado', 1, 0, 'C', true);
$pdf->Cell(42, 8, 'Responsables', 1, 0, 'C', true);
$pdf->Cell(53, 8, utf8_decode('Descripción'), 1, 1, 'C', true);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(32, 33, 36);

<<<<<<< HEAD
$printTableHeader = static function (FPDF $pdf) use ($wId, $wFecha, $wArea, $wEstado, $wDuracion, $wResp, $wDesc): void {
    $pdf->SetFillColor(232, 240, 254);
    $pdf->SetTextColor(32, 33, 36);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell($wId, 8, '#', 1, 0, 'C', true);
    $pdf->Cell($wFecha, 8, 'Fecha', 1, 0, 'C', true);
    $pdf->Cell($wArea, 8, utf8_decode('Área'), 1, 0, 'C', true);
    $pdf->Cell($wEstado, 8, 'Estado', 1, 0, 'C', true);
    $pdf->Cell($wDuracion, 8, utf8_decode('Duración'), 1, 0, 'C', true);
    $pdf->Cell($wResp, 8, 'Responsables', 1, 0, 'C', true);
    $pdf->Cell($wDesc, 8, utf8_decode('Descripción'), 1, 1, 'C', true);
=======
$printTableHeader = static function (FPDF $pdf): void {
    $pdf->SetFillColor(232, 240, 254);
    $pdf->SetTextColor(32, 33, 36);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(12, 8, '#', 1, 0, 'C', true);
    $pdf->Cell(25, 8, 'Fecha', 1, 0, 'C', true);
    $pdf->Cell(28, 8, utf8_decode('Área'), 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'Estado', 1, 0, 'C', true);
    $pdf->Cell(42, 8, 'Responsables', 1, 0, 'C', true);
    $pdf->Cell(53, 8, utf8_decode('Descripción'), 1, 1, 'C', true);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

    $pdf->SetFont('Arial', '', 8);
    $pdf->SetTextColor(32, 33, 36);
};

$nbLines = static function (FPDF $pdf, float $w, string $txt): int {
    // Based on FPDF::NbLines() logic (core-table pattern)
    $cw = $pdf->CurrentFont['cw'] ?? [];
    if ($w <= 0) {
        $w = $pdf->w - $pdf->rMargin - $pdf->x;
    }
    $wmax = ($w - 2 * 1) * 1000 / 8; // Usar tamaño de fuente fijo de 8 puntos (Arial, tamaño actual)
    $s = str_replace("\r", '', (string) $txt);
    $nb = strlen($s);
    if ($nb > 0 && $s[$nb - 1] === "\n") {
        $nb--;
    }
    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $nl = 1;
    while ($i < $nb) {
        $c = $s[$i];
        if ($c === "\n") {
            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $nl++;
            continue;
        }
        if ($c === ' ') {
            $sep = $i;
        }
        $l += $cw[$c] ?? 0;
        if ($l > $wmax) {
            if ($sep === -1) {
                if ($i === $j) {
                    $i++;
                }
            } else {
                $i = $sep + 1;
            }
            $sep = -1;
            $j = $i;
            $l = 0;
            $nl++;
        } else {
            $i++;
        }
    }
    return $nl;
};

while ($row = $res->fetch_assoc()) {
    $responsables = json_decode($row['responsables_data'] ?? '[]', true);
    $nombres = array_map(static function ($r) {
        return mb_substr($r['nombre'] ?? '', 0, 30);
    }, is_array($responsables) ? $responsables : []);
    $nombres = array_values(array_filter(array_map('trim', $nombres), static fn ($v) => $v !== ''));

    if (count($nombres) > 5) {
        $nombres = array_slice($nombres, 0, 5);
        $nombres[] = '...';
    }

    // ── Separar nombres con salto de línea para que cada uno ocupe su propia línea ──
    $responsablesTxt = implode("\n", $nombres);
    $descripcion = mb_substr((string) ($row['descripcion'] ?? ''), 0, 150);

<<<<<<< HEAD
=======
    $wId     = 12;
    $wFecha  = 25;
    $wArea   = 28;
    $wEstado = 30;
    $wResp   = 42;
    $wDesc   = 53;
    $lineH   = 4.0;

>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    $respPdf   = utf8_decode($responsablesTxt);
    $descPdf   = utf8_decode($descripcion);
    $areaPdf   = utf8_decode(mb_substr((string) $row['area'], 0, 50));
    $estadoPdf = utf8_decode(mb_substr((string) $row['estado'], 0, 15));

<<<<<<< HEAD
    // ── Duración (solo actividades finalizadas) ──
    $esFinalizada = ($row['estado'] ?? '') === 'Finalizada';
    if ($esFinalizada && !empty($row['fecha_fin'])) {
        $duracionTexto = formatearDuracion($row['fecha_inicio'], $row['fecha_fin']);
    } else {
        $duracionTexto = '—';
    }
    $duracionPdf = utf8_decode(mb_substr($duracionTexto, 0, 20));

    $lineH   = 4.0;

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    // ── Calcular altura real de las celdas multi-línea ANTES de dibujar ──
    $pdf->SetFont('Arial', '', 8);
    $linesResp = $nbLines($pdf, $wResp, $respPdf);
    $linesDesc = $nbLines($pdf, $wDesc, $descPdf);
    $maxLines  = max(1, $linesResp, $linesDesc);
<<<<<<< HEAD
    $rowHeight = $maxLines * $lineH;
=======
    $rowHeight = $maxLines * $lineH;  // altura total sincronizada de la fila
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

    $y = $pdf->GetY();
    $pageBreakTrigger = $pdf->GetPageHeight() - 20;

<<<<<<< HEAD
=======
    // Verificar salto de página CONSIDERANDO la altura real de la fila
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    if ($y + $rowHeight > $pageBreakTrigger) {
        $pdf->AddPage();
        $printTableHeader($pdf);
        $pdf->SetFont('Arial', '', 8);
        $y = $pdf->GetY();
    }

<<<<<<< HEAD
    // ── Dibujar toda la fila con bordes uniformes (Rect) ──
    $x = $pdf->GetX();

=======
    // ── Dibujar toda la fila con bordes uniformes (Rect) y texto centrado ──
    $x = $pdf->GetX();

    // Posiciones X acumuladas para cada columna
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    $x0 = $x;
    $x1 = $x0 + $wId;
    $x2 = $x1 + $wFecha;
    $x3 = $x2 + $wArea;
    $x4 = $x3 + $wEstado;
<<<<<<< HEAD
    $x5 = $x4 + $wDuracion;
    $x6 = $x5 + $wResp;

    $pdf->Rect($x0, $y, $wId,      $rowHeight);
    $pdf->Rect($x1, $y, $wFecha,   $rowHeight);
    $pdf->Rect($x2, $y, $wArea,    $rowHeight);
    $pdf->Rect($x3, $y, $wEstado,  $rowHeight);
    $pdf->Rect($x4, $y, $wDuracion,$rowHeight);
    $pdf->Rect($x5, $y, $wResp,    $rowHeight);
    $pdf->Rect($x6, $y, $wDesc,    $rowHeight);

=======
    $x5 = $x4 + $wResp;

    // 1) Dibujar bordes de TODAS las columnas con Rect (altura uniforme)
    $pdf->Rect($x0, $y, $wId,     $rowHeight);
    $pdf->Rect($x1, $y, $wFecha,  $rowHeight);
    $pdf->Rect($x2, $y, $wArea,   $rowHeight);
    $pdf->Rect($x3, $y, $wEstado, $rowHeight);
    $pdf->Rect($x4, $y, $wResp,   $rowHeight);
    $pdf->Rect($x5, $y, $wDesc,   $rowHeight);

    // 2) Texto centrado verticalmente para columnas de una sola línea
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    $textY = $y + ($rowHeight - $lineH) / 2;

    $pdf->SetXY($x0, $textY);
    $pdf->Cell($wId, $lineH, (string) $row['id'], 0, 0, 'C');

    $pdf->SetXY($x1, $textY);
    $pdf->Cell($wFecha, $lineH, date('d-m-Y', strtotime($row['fecha_inicio'])), 0, 0, 'C');

    $pdf->SetXY($x2, $textY);
    $pdf->Cell($wArea, $lineH, $areaPdf, 0, 0, 'L');

    $pdf->SetXY($x3, $textY);
    $pdf->Cell($wEstado, $lineH, $estadoPdf, 0, 0, 'C');

<<<<<<< HEAD
    $pdf->SetXY($x4, $textY);
    $pdf->Cell($wDuracion, $lineH, $duracionPdf, 0, 0, 'C');

    $pdf->SetXY($x5, $y);
    $pdf->MultiCell($wResp, $lineH, $respPdf, 0, 'L');

    $pdf->SetXY($x6, $y);
    $pdf->MultiCell($wDesc, $lineH, $descPdf, 0, 'L');

=======
    // 3) MultiCell sin borde para responsables (cada nombre en su línea)
    $pdf->SetXY($x4, $y);
    $pdf->MultiCell($wResp, $lineH, $respPdf, 0, 'L');

    // 4) MultiCell sin borde para descripción
    $pdf->SetXY($x5, $y);
    $pdf->MultiCell($wDesc, $lineH, $descPdf, 0, 'L');

    // Avanzar Y al final de la fila
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    $pdf->SetXY($x, $y + $rowHeight);
}

$pdf->Output('I', 'reporte_actividades.pdf');
?>
