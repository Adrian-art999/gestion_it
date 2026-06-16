-- Eliminar índices UNIQUE que causan error de duplicidad falsa
-- Este script debe ejecutarse una sola vez para corregir el problema

-- Eliminar índice UNIQUE del campo username si existe
DROP INDEX IF EXISTS username ON usuarios;

-- Eliminar cualquier otro índice UNIQUE que pueda estar causando el error
DROP INDEX IF EXISTS usuarios_username_unique ON usuarios;
DROP INDEX IF UNIQUE IF EXISTS idx_username ON usuarios;

-- Verificar estructura final
SHOW INDEX FROM usuarios;
