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
<<<<<<< HEAD

if (!function_exists('asegurarColumnaFechaFin')) {
    function asegurarColumnaFechaFin(mysqli $conn): void
    {
        if (!columnaExiste($conn, 'actividades', 'fecha_fin')) {
            $conn->query("ALTER TABLE actividades ADD COLUMN fecha_fin DATETIME NULL AFTER fecha_inicio");
        }
    }
}

if (!function_exists('asegurarTablaLogs')) {
    function asegurarTablaLogs(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS logs_sistema (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    usuario_id INT NOT NULL,
                    accion TEXT NOT NULL,
                    fecha DATE NOT NULL,
                    INDEX idx_logs_fecha (fecha),
                    INDEX idx_logs_usuario (usuario_id),
                    CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $conn->query($sql);
    }
}

if (!function_exists('registrar_log')) {
    function registrar_log(mysqli $conn, int $usuario_id, string $accion): void
    {
        date_default_timezone_set('America/Caracas');
        asegurarTablaLogs($conn);
        $fecha = date('Y-m-d');
        $sql = "INSERT INTO logs_sistema (usuario_id, accion, fecha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('iss', $usuario_id, $accion, $fecha);
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
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
?>
