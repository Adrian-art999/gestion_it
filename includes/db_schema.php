<?php
if (!function_exists('columnaExiste')) {
    function columnaExiste(mysqli $conn, string $tabla, string $columna): bool
    {
        $sql = "SELECT 1
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $tabla, $columna);
        $stmt->execute();
        $res = $stmt->get_result();
        return (bool) $res->fetch_row();
    }
}

if (!function_exists('asegurarColumnasUsuarios')) {
    /**
     * Asegura que la tabla usuarios tenga las columnas necesarias.
     * ELIMINADO: La estructura de la tabla usuarios ya está fija y limpia.
     * Ya no se ejecuta ALTER TABLE en cada carga.
     * La columna 'usuario' fue eliminada y los índices UNIQUE están correctos.
     */
    function asegurarColumnasUsuarios(mysqli $conn): void
    {
        // Función deshabilitada - estructura de DB ya está fija
        // Los índices UNIQUE en username y correo ya existen
    }
}

if (!function_exists('asegurarTablaHistorialActividades')) {
    function asegurarTablaHistorialActividades(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS actividad_historial (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    actividad_id INT NOT NULL,
                    accion VARCHAR(120) NOT NULL,
                    detalle TEXT NULL,
                    usuario_id INT NULL,
                    usuario_nombre VARCHAR(160) NULL,
                    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_hist_actividad (actividad_id),
                    INDEX idx_hist_usuario (usuario_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $conn->query($sql);
    }
}

if (!function_exists('asegurarTablaRecuperacionUsuarios')) {
    function asegurarTablaRecuperacionUsuarios(mysqli $conn): void
    {
        $sql = "CREATE TABLE IF NOT EXISTS usuario_recuperacion (
                    usuario_id INT PRIMARY KEY,
                    pregunta_1 VARCHAR(80) NOT NULL,
                    respuesta_1_hash CHAR(64) NOT NULL,
                    pregunta_2 VARCHAR(80) NOT NULL,
                    respuesta_2_hash CHAR(64) NOT NULL,
                    pregunta_3 VARCHAR(80) NOT NULL,
                    respuesta_3_hash CHAR(64) NOT NULL,
                    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    CONSTRAINT fk_recuperacion_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $conn->query($sql);
    }
}

if (!function_exists('asegurarColumnasEmpleados')) {
    /**
     * Asegura que la tabla empleados tenga las columnas necesarias.
     * ELIMINADO: La estructura de la tabla empleados ya está fija y limpia.
     * Ya no se ejecuta ALTER TABLE en cada carga.
     */
    function asegurarColumnasEmpleados(mysqli $conn): void
    {
        // Función deshabilitada - estructura de DB ya está fija
    }
}

if (!function_exists('asegurarEstadoActividades')) {
    function asegurarEstadoActividades(mysqli $conn): void
    {
        $sql = "SELECT COLUMN_TYPE
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'actividades'
                  AND COLUMN_NAME = 'estado'
                LIMIT 1";
        $res = $conn->query($sql);
        $row = $res ? $res->fetch_assoc() : null;
        $tipo = strtolower((string) ($row['COLUMN_TYPE'] ?? ''));

        // Normalizar valores fuera de los 3 estados oficiales.
        $conn->query("UPDATE actividades SET estado = 'En progreso' WHERE estado NOT IN ('En progreso','Finalizada','Cancelada')");

        // Forzar el ENUM a los 3 estados oficiales si difiere.
        if ($tipo !== '' && str_contains($tipo, 'enum(') && $tipo !== "enum('en progreso','finalizada','cancelada')") {
            $conn->query("ALTER TABLE actividades MODIFY COLUMN estado ENUM('En progreso','Finalizada','Cancelada') NOT NULL DEFAULT 'En progreso'");
        }
    }
}
?>
