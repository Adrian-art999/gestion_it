-- Migración: Permisos Granulares
-- Añade columna `permisos` (TEXT JSON) a la tabla `usuarios`.
-- Usuarios existentes reciben permisos completos (backward compatibility).
-- Nuevos usuarios creados con rol='tecnico' obtendrán permisos restringidos por defecto.

ALTER TABLE usuarios
  ADD COLUMN permisos TEXT DEFAULT NULL
  COMMENT 'JSON con permisos granulares. NULL = hereda defaults según rol. FORMATO: {"actividades_editar":true,"actividades_eliminar":false,...}'
  AFTER recuperacion_actualizado_en;

-- Para usuarios existentes con rol='admin': permisos completos
UPDATE usuarios
SET permisos = '{"actividades_editar":true,"actividades_eliminar":true,"actividades_finalizar":true,"actividades_info":true,"empleados_listar":true,"empleados_registrar":true,"empleados_editar":true,"empleados_eliminar":true,"empleados_info":true,"usuarios_listar":true,"usuarios_registrar":true,"usuarios_editar":true,"usuarios_eliminar":true,"reportes_pdf":true,"bitacora":true,"roles_gestionar":true}'
WHERE rol = 'admin' AND permisos IS NULL;

-- Para usuarios existentes con rol='tecnico': permisos restringidos (Usuario Común)
UPDATE usuarios
SET permisos = '{"actividades_editar":true,"actividades_eliminar":false,"actividades_finalizar":true,"actividades_info":true,"empleados_listar":true,"empleados_registrar":false,"empleados_editar":false,"empleados_eliminar":false,"empleados_info":false,"usuarios_listar":false,"usuarios_registrar":false,"usuarios_editar":false,"usuarios_eliminar":false,"reportes_pdf":false,"bitacora":false,"roles_gestionar":false}'
WHERE rol = 'tecnico' AND permisos IS NULL;
