-- Migración: Tabla de Bitácora (logs_sistema)
-- CREADO: 2025-06-14
-- Registro simple de acciones: quién, qué, cuándo.

CREATE TABLE IF NOT EXISTS logs_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    accion TEXT NOT NULL,
    fecha DATE NOT NULL,
    INDEX idx_logs_fecha (fecha),
    INDEX idx_logs_usuario (usuario_id),
    CONSTRAINT fk_logs_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
