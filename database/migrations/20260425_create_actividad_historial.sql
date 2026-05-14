CREATE TABLE IF NOT EXISTS actividad_historial (
    id INT AUTO_INCREMENT PRIMARY KEY,
    actividad_id INT NOT NULL,
    accion VARCHAR(120) NOT NULL,
    detalle TEXT NULL,
    usuario_id INT NULL,
    usuario_nombre VARCHAR(160) NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_hist_actividad (actividad_id),
    INDEX idx_hist_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
