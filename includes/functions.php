<?php
if (!function_exists('nd')) {
    function nd($value): string
    {
        $text = trim((string) ($value ?? ''));
        return $text === '' ? 'N/D' : $text;
    }
}

if (!function_exists('requireAuthJson')) {
    function requireAuthJson(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Sesión vencida']);
            exit;
        }
    }
}

if (!function_exists('normalizarEstadosActividades')) {
    function normalizarEstadosActividades(mysqli $conn): void
    {
        // El sistema solo maneja 3 estados manuales.
        $sql = "UPDATE actividades
                SET estado = 'En progreso'
                WHERE estado NOT IN ('En progreso','Finalizada','Cancelada')";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            return;
        }
        $stmt->execute();
    }
}

if (!function_exists('asegurarColumnaFechaFin')) {
    function asegurarColumnaFechaFin(mysqli $conn): void
    {
        if (!columnaExiste($conn, 'actividades', 'fecha_fin')) {
            $conn->query("ALTER TABLE actividades ADD COLUMN fecha_fin DATETIME NULL AFTER fecha_inicio");
        }
    }
}

if (!function_exists('capitalizarNombre')) {
    function capitalizarNombre(string $nombre): string
    {
        $nombre = trim($nombre);
        $nombre = mb_strtolower($nombre, 'UTF-8');
        return mb_convert_case($nombre, MB_CASE_TITLE, 'UTF-8');
    }
}

if (!function_exists('asegurarTablaLogs')) {
    function asegurarTablaLogs(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS logs_sistema (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    usuario_id INT NOT NULL,
                    accion TEXT NOT NULL,
                    detalle TEXT NULL,
                    fecha DATE NOT NULL,
                    INDEX idx_logs_fecha (fecha),
                    INDEX idx_logs_usuario (usuario_id),
                    CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $conn->query($sql);
    }
}

if (!function_exists('registrar_log')) {
    function registrar_log(mysqli $conn, int $usuario_id, string $accion, ?string $detalle = null): void
    {
        date_default_timezone_set('America/Caracas');
        asegurarTablaLogs($conn);
        asegurarColumnaDetalleLogs($conn);
        $fecha = date('Y-m-d');
        $sql = "INSERT INTO logs_sistema (usuario_id, accion, detalle, fecha) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('isss', $usuario_id, $accion, $detalle, $fecha);
            $stmt->execute();
            $stmt->close();
        }
    }
}

if (!function_exists('construir_mensaje_cambios')) {
    function construir_mensaje_cambios(array $cambios, string $sufijo): string
    {
        if (empty($cambios)) {
            return "Actualizó {$sufijo}";
        }
        if (count($cambios) === 1) {
            return $cambios[0] . " {$sufijo}";
        }
        $ultimo = array_pop($cambios);
        return implode(', ', $cambios) . " y {$ultimo} {$sufijo}";
    }
}

if (!function_exists('formatearAccionBitacora')) {
    function formatearAccionBitacora(string $accion, ?string $detalle = null, int $maxLen = 90): array
    {
        $textoBase = preg_replace('/\s*(\.\.\.)?\s*\(Ver info\)\s*$/u', '', $accion);
        $textoBase = trim($textoBase);
        $tieneDetalle = !empty($detalle);
        $textoFinal = $textoBase;

        if ($tieneDetalle) {
            $detalleObj = json_decode($detalle, true);
            if ($detalleObj && isset($detalleObj['tipo']) && $detalleObj['tipo'] === 'actividad' && isset($detalleObj['cambios']) && is_array($detalleObj['cambios'])) {
                $totalCambios = count($detalleObj['cambios']);
                if ($totalCambios > 2) {
                    $parts = preg_split('/\s*,\s*|\s+y\s+/u', $textoBase);
                    if (count($parts) >= 3) {
                        $fragments = array_slice($parts, 0, 2);
                        $restantes = $totalCambios - 2;
                        $textoFinal = implode(', ', $fragments) . ' y ' . $restantes . ($restantes === 1 ? ' cambio más' : ' cambios más');
                    }
                }
            }
        }

        $textoFinal = mb_strtoupper(mb_substr($textoFinal, 0, 1)) . mb_substr($textoFinal, 1);

        if (mb_strlen($textoFinal) > $maxLen) {
            $truncado = mb_substr($textoFinal, 0, $maxLen);
            $ultimoEspacio = mb_strrpos($truncado, ' ');
            if ($ultimoEspacio !== false) {
                $textoFinal = mb_substr($truncado, 0, $ultimoEspacio) . '...';
            } else {
                $textoFinal = $truncado . '...';
            }
        }

        return [
            'texto' => $textoFinal,
            'tieneDetalle' => $tieneDetalle,
            'textoCompleto' => $textoBase,
        ];
    }
}

if (!function_exists('asegurarColumnaDetalleLogs')) {
    function asegurarColumnaDetalleLogs(mysqli $conn): void
    {
        $r = $conn->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'logs_sistema' AND COLUMN_NAME = 'detalle' LIMIT 1");
        if ($r && $r->num_rows === 0) {
            $conn->query("ALTER TABLE logs_sistema ADD COLUMN detalle TEXT NULL AFTER accion");
        }
    }
}

if (!function_exists('formatearDuracion')) {
    function formatearDuracion(string $fecha_inicio, ?string $fecha_fin): string
    {
        if (empty($fecha_fin)) {
            return '—';
        }
        try {
            $inicio = new DateTime($fecha_inicio);
            $fin = new DateTime($fecha_fin);
            $diff = $inicio->diff($fin);

            $dias = (int) $diff->format('%a');
            $horas = (int) $diff->format('%h');
            $minutos = (int) $diff->format('%i');

            if ($dias > 0) {
                $diasTexto = $dias === 1 ? '1 Día' : "{$dias} Días";
                if ($horas > 0) {
                    $horasTexto = $horas === 1 ? '1 Hora' : "{$horas} Horas";
                    return "{$diasTexto} y {$horasTexto}";
                }
                return $diasTexto;
            }
            if ($horas > 0) {
                $horasTexto = $horas === 1 ? '1 Hora' : "{$horas} Horas";
                if ($minutos > 0) {
                    $minutosTexto = $minutos === 1 ? '1 Minuto' : "{$minutos} Minutos";
                    return "{$horasTexto} y {$minutosTexto}";
                }
                return $horasTexto;
            }
            $minutosTexto = $minutos === 1 ? '1 Minuto' : "{$minutos} Minutos";
            return $minutosTexto;
        } catch (Exception $e) {
            return 'N/D';
        }
    }
}
?>
