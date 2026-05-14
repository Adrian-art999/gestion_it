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
?>
