-- ═══════════════════════════════════════════════════════════════════
-- fix_username_duplicates.sql
-- O.S.T.I. — Corrección del Error 409 (Duplicidad Fantasma)
-- Ejecutar UNA SOLA VEZ desde phpMyAdmin o la consola de MySQL.
-- ═══════════════════════════════════════════════════════════════════

-- 1. Ver qué registros tienen username vacío o NULL
SELECT id, nombre_completo, username, correo
FROM usuarios
WHERE username IS NULL OR TRIM(username) = '';

-- 2. Eliminar los registros con username vacío o NULL
--    (registros fantasma que provocan el Duplicate entry '')
DELETE FROM usuarios
WHERE username IS NULL OR TRIM(username) = '';

-- 3. Eliminar índices UNIQUE existentes sobre username (si los hay)
--    para poder recrearlos correctamente.
SET @idx_exists = (
    SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME   = 'usuarios'
      AND INDEX_NAME   = 'idx_unique_username'
);

-- Solo intentar DROP si existe (evita error en entornos limpios)
-- Ejecuta manualmente si el SET/IF no es compatible con tu cliente:
-- DROP INDEX idx_unique_username ON usuarios;

-- Forma segura de eliminar el índice (compatible con MySQL 5.7+):
ALTER TABLE usuarios DROP INDEX IF EXISTS idx_unique_username;
ALTER TABLE usuarios DROP INDEX IF EXISTS username;
ALTER TABLE usuarios DROP INDEX IF EXISTS usuarios_username_unique;

-- 4. Restaurar el índice UNIQUE correcto en la columna username.
--    Solo indexa valores NOT NULL y no vacíos (la columna debe ser NOT NULL
--    para que esto funcione; si admite NULL usa UNIQUE directamente).
ALTER TABLE usuarios
    MODIFY COLUMN username VARCHAR(60) NOT NULL DEFAULT '',
    ADD UNIQUE INDEX idx_unique_username (username);

-- 5. Verificación final
SHOW INDEX FROM usuarios WHERE Key_name = 'idx_unique_username';
SELECT id, nombre_completo, username FROM usuarios ORDER BY id;
