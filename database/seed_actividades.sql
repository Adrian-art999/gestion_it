-- ============================================================
-- Seed: 70 actividades de prueba con variedad de estados,
-- responsables, áreas y fechas.
-- Database: sistema_it
-- ============================================================
USE sistema_it;

-- Empleados disponibles: IDs 22-31, 40
-- Usuarios: ID 72 (Gladys Muñoz), 74 (Jose Ventura)
-- Áreas: Informática, Sistemas, Redes, Soporte Técnico,
--        Desarrollo, Infraestructura, Seguridad, Base de Datos

INSERT INTO actividades (descripcion, area, estado, responsables_data, fecha_inicio, fecha_fin, id_usuario) VALUES

-- ── 30 EN PROGRESO ──────────────────────────────────────────
('Actualización de firmware en switches de núcleo de la red principal', 'Redes', 'En progreso',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":25,"nombre":"Yedcibel Diaz"}]',
 '2026-04-28 10:30:00', NULL, 72),

('Migración de servidor de archivos a nueva plataforma NAS corporativa', 'Infraestructura', 'En progreso',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":30,"nombre":"Gladys Muñoz"},{"id":31,"nombre":"Daniel Sanchez"}]',
 '2026-05-02 08:00:00', NULL, 72),

('Instalación de certificados SSL en todos los sitios internos', 'Seguridad', 'En progreso',
 '[{"id":24,"nombre":"Jorgue Lazaro"}]',
 '2026-05-05 14:15:00', NULL, 72),

('Configuración de VPN site-to-site para sucursal Maracaibo', 'Redes', 'En progreso',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-05-06 09:00:00', NULL, 72),

('Despliegue de actualización de seguridad en estaciones Windows', 'Soporte Técnico', 'En progreso',
 '[{"id":27,"nombre":"Nakary Garcia"},{"id":28,"nombre":"Andreina Medina"},{"id":29,"nombre":"Julio Acosta"}]',
 '2026-05-08 11:00:00', NULL, 72),

('Revisión de políticas de backup y restauración de datos críticos', 'Infraestructura', 'En progreso',
 '[{"id":30,"nombre":"Gladys Muñoz"}]',
 '2026-05-10 07:30:00', NULL, 72),

('Desarrollo de nuevo módulo de reportes para el sistema O.S.T.I.', 'Desarrollo', 'En progreso',
 '[{"id":24,"nombre":"Jorgue Lazaro"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-05-11 13:45:00', NULL, 74),

('Optimización de consultas SQL en base de datos principal', 'Base de Datos', 'En progreso',
 '[{"id":31,"nombre":"Daniel Sanchez"}]',
 '2026-05-12 10:00:00', NULL, 72),

('Configuración de monitoreo con Zabbix para servidores críticos', 'Sistemas', 'En progreso',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-05-13 08:30:00', NULL, 74),

('Inventario de equipos de red y actualización de base de datos activos', 'Redes', 'En progreso',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":25,"nombre":"Yedcibel Diaz"},{"id":29,"nombre":"Julio Acosta"}]',
 '2026-05-14 09:15:00', NULL, 72),

('Implementación de autenticación multifactor para acceso remoto', 'Seguridad', 'En progreso',
 '[{"id":27,"nombre":"Nakary Garcia"}]',
 '2026-05-15 11:30:00', NULL, 72),

('Actualización de sistema operativo en servidores de base de datos', 'Base de Datos', 'En progreso',
 '[{"id":31,"nombre":"Daniel Sanchez"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-05-16 14:00:00', NULL, 74),

('Reparación de cableado estructurado en edificio administrativo', 'Infraestructura', 'En progreso',
 '[{"id":28,"nombre":"Andreina Medina"}]',
 '2026-05-18 07:45:00', NULL, 72),

('Capacitación al personal sobre nuevas políticas de seguridad informática', 'Seguridad', 'En progreso',
 '[{"id":30,"nombre":"Gladys Muñoz"},{"id":27,"nombre":"Nakary Garcia"}]',
 '2026-05-19 10:00:00', NULL, 72),

('Corrección de errores en el módulo de nómina del ERP interno', 'Desarrollo', 'En progreso',
 '[{"id":24,"nombre":"Jorgue Lazaro"}]',
 '2026-05-20 13:30:00', NULL, 74),

('Configuración de firewall perimetral para nueva sede', 'Redes', 'En progreso',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-05-21 08:00:00', NULL, 72),

('Instalación de puntos de acceso WiFi en áreas comunes del piso 3', 'Redes', 'En progreso',
 '[{"id":25,"nombre":"Yedcibel Diaz"},{"id":29,"nombre":"Julio Acosta"}]',
 '2026-05-22 09:30:00', NULL, 72),

('Migración de correo electrónico a plataforma cloud', 'Sistemas', 'En progreso',
 '[{"id":23,"nombre":"Edmundo Oberto"}]',
 '2026-05-25 11:15:00', NULL, 74),

('Desarrollo de API REST para integración con sistema contable', 'Desarrollo', 'En progreso',
 '[{"id":40,"nombre":"Emmanuel Lujan"},{"id":24,"nombre":"Jorgue Lazaro"}]',
 '2026-05-26 14:00:00', NULL, 74),

('Revisión de licencias de software y renovación de suscripciones', 'Soporte Técnico', 'En progreso',
 '[{"id":28,"nombre":"Andreina Medina"},{"id":27,"nombre":"Nakary Garcia"}]',
 '2026-05-27 10:30:00', NULL, 72),

('Configuración de respaldo automatizado para base de datos de producción', 'Base de Datos', 'En progreso',
 '[{"id":31,"nombre":"Daniel Sanchez"}]',
 '2026-05-28 08:45:00', NULL, 72),

('Actualización de parches de seguridad en servidores Linux', 'Sistemas', 'En progreso',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":26,"nombre":"Neri la Cruz"},{"id":30,"nombre":"Gladys Muñoz"}]',
 '2026-05-29 07:00:00', NULL, 72),

('Instalación de sistema de respaldo eléctrico para sala de servidores', 'Infraestructura', 'En progreso',
 '[{"id":28,"nombre":"Andreina Medina"}]',
 '2026-06-01 09:00:00', NULL, 72),

('Auditoría de accesos a sistemas internos y revisión de roles', 'Seguridad', 'En progreso',
 '[{"id":27,"nombre":"Nakary Garcia"},{"id":30,"nombre":"Gladys Muñoz"}]',
 '2026-06-02 11:00:00', NULL, 72),

('Implementación de cola de tickets para soporte técnico', 'Desarrollo', 'En progreso',
 '[{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-06-03 13:00:00', NULL, 74),

('Configuración de VLANs para segmentación de red corporativa', 'Redes', 'En progreso',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":25,"nombre":"Yedcibel Diaz"}]',
 '2026-06-04 10:15:00', NULL, 72),

('Actualización de drivers y firmware en servidores HP', 'Soporte Técnico', 'En progreso',
 '[{"id":29,"nombre":"Julio Acosta"},{"id":28,"nombre":"Andreina Medina"}]',
 '2026-06-05 14:30:00', NULL, 72),

('Refactorización del módulo de autenticación del sistema O.S.T.I.', 'Desarrollo', 'En progreso',
 '[{"id":24,"nombre":"Jorgue Lazaro"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-06-08 09:00:00', NULL, 74),

('Pruebas de penetración en aplicación web interna', 'Seguridad', 'En progreso',
 '[{"id":27,"nombre":"Nakary Garcia"}]',
 '2026-06-09 11:45:00', NULL, 72),

('Monitoreo de rendimiento de base de datos durante horas pico', 'Base de Datos', 'En progreso',
 '[{"id":31,"nombre":"Daniel Sanchez"},{"id":23,"nombre":"Edmundo Oberto"}]',
 '2026-06-10 08:00:00', NULL, 72),

-- ── 35 FINALIZADAS ──────────────────────────────────────────
('Configuración inicial de Active Directory para nuevos empleados', 'Sistemas', 'Finalizada',
 '[{"id":23,"nombre":"Edmundo Oberto"}]',
 '2026-03-02 09:00:00', '2026-03-04 16:30:00', 72),

('Reemplazo de switch principal del piso 1 por unidad gigabit', 'Redes', 'Finalizada',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":25,"nombre":"Yedcibel Diaz"}]',
 '2026-03-05 08:30:00', '2026-03-06 17:00:00', 72),

('Limpieza y mantenimiento preventivo de servidores en sala', 'Infraestructura', 'Finalizada',
 '[{"id":28,"nombre":"Andreina Medina"},{"id":29,"nombre":"Julio Acosta"}]',
 '2026-03-08 07:00:00', '2026-03-08 15:30:00', 72),

('Actualización de antivirus corporativo a versión enterprise', 'Seguridad', 'Finalizada',
 '[{"id":27,"nombre":"Nakary Garcia"}]',
 '2026-03-10 10:00:00', '2026-03-11 14:00:00', 72),

('Migración de base de datos de inventario a MySQL 8', 'Base de Datos', 'Finalizada',
 '[{"id":31,"nombre":"Daniel Sanchez"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-03-12 09:30:00', '2026-03-15 18:00:00', 74),

('Instalación de sistema operativo en 15 estaciones de trabajo nuevas', 'Soporte Técnico', 'Finalizada',
 '[{"id":28,"nombre":"Andreina Medina"}]',
 '2026-03-15 08:00:00', '2026-03-18 17:00:00', 72),

('Desarrollo de módulo de recuperación de contraseña', 'Desarrollo', 'Finalizada',
 '[{"id":24,"nombre":"Jorgue Lazaro"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-03-17 13:00:00', '2026-03-20 16:00:00', 74),

('Configuración de impresión centralizada para departamento contable', 'Sistemas', 'Finalizada',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-03-19 10:15:00', '2026-03-20 12:30:00', 72),

('Inventario y etiquetado de equipos del departamento de RRHH', 'Infraestructura', 'Finalizada',
 '[{"id":29,"nombre":"Julio Acosta"}]',
 '2026-03-22 09:00:00', '2026-03-22 16:45:00', 72),

('Corrección de vulnerabilidad SQL injection en formulario web', 'Seguridad', 'Finalizada',
 '[{"id":27,"nombre":"Nakary Garcia"},{"id":24,"nombre":"Jorgue Lazaro"}]',
 '2026-03-24 11:30:00', '2026-03-26 15:00:00', 72),

('Configuración de backup remoto para servidor de archivos', 'Infraestructura', 'Finalizada',
 '[{"id":30,"nombre":"Gladys Muñoz"},{"id":31,"nombre":"Daniel Sanchez"}]',
 '2026-03-26 08:00:00', '2026-03-27 14:30:00', 72),

('Instalación de certificado digital en portal de proveedores', 'Redes', 'Finalizada',
 '[{"id":22,"nombre":"Luis Ruiz"}]',
 '2026-03-30 10:00:00', '2026-03-30 16:00:00', 72),

('Actualización de ERP a versión 2026 Q1 con parches contables', 'Sistemas', 'Finalizada',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-04-01 09:00:00', '2026-04-04 18:00:00', 74),

('Creación de script automatizado para limpieza de logs del sistema', 'Desarrollo', 'Finalizada',
 '[{"id":24,"nombre":"Jorgue Lazaro"}]',
 '2026-04-02 14:00:00', '2026-04-03 12:00:00', 74),

('Revisión de seguridad en aplicaciones web del banco', 'Seguridad', 'Finalizada',
 '[{"id":27,"nombre":"Nakary Garcia"},{"id":30,"nombre":"Gladys Muñoz"}]',
 '2026-04-05 08:30:00', '2026-04-08 17:00:00', 72),

('Instalación de proxy inverso para balanceo de carga', 'Redes', 'Finalizada',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-04-07 09:15:00', '2026-04-09 13:45:00', 72),

('Optimización de tablas en base de datos de producción', 'Base de Datos', 'Finalizada',
 '[{"id":31,"nombre":"Daniel Sanchez"}]',
 '2026-04-09 11:00:00', '2026-04-09 17:30:00', 72),

('Asistencia técnica a usuario VIP con problemas de conectividad', 'Soporte Técnico', 'Finalizada',
 '[{"id":28,"nombre":"Andreina Medina"},{"id":29,"nombre":"Julio Acosta"}]',
 '2026-04-12 09:00:00', '2026-04-12 11:00:00', 72),

('Desarrollo de página de estado del sistema para usuarios internos', 'Desarrollo', 'Finalizada',
 '[{"id":40,"nombre":"Emmanuel Lujan"},{"id":24,"nombre":"Jorgue Lazaro"}]',
 '2026-04-14 13:00:00', '2026-04-16 16:00:00', 74),

('Instalación de sistema CCTV en sala de servidores', 'Infraestructura', 'Finalizada',
 '[{"id":28,"nombre":"Andreina Medina"}]',
 '2026-04-16 07:30:00', '2026-04-17 15:00:00', 72),

('Configuración de grupo de alta disponibilidad para SQL Server', 'Base de Datos', 'Finalizada',
 '[{"id":31,"nombre":"Daniel Sanchez"},{"id":23,"nombre":"Edmundo Oberto"}]',
 '2026-04-18 10:00:00', '2026-04-22 18:00:00', 72),

('Actualización de firmware en almacenamiento SAN', 'Redes', 'Finalizada',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":25,"nombre":"Yedcibel Diaz"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-04-20 08:00:00', '2026-04-21 17:00:00', 72),

('Capacitación sobre phishing y seguridad para todo el personal', 'Seguridad', 'Finalizada',
 '[{"id":27,"nombre":"Nakary Garcia"},{"id":30,"nombre":"Gladys Muñoz"}]',
 '2026-04-22 10:30:00', '2026-04-23 16:00:00', 72),

('Creación de imagen corporativa para estaciones de trabajo', 'Soporte Técnico', 'Finalizada',
 '[{"id":29,"nombre":"Julio Acosta"}]',
 '2026-04-24 09:00:00', '2026-04-25 14:00:00', 72),

('Reestructuración de directorio activo y grupos de seguridad', 'Sistemas', 'Finalizada',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-04-27 08:00:00', '2026-04-29 17:30:00', 72),

('Desarrollo de widget de clima laboral para intranet', 'Desarrollo', 'Finalizada',
 '[{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-04-29 13:00:00', '2026-04-30 15:00:00', 74),

('Instalación de sistema de naming para servidores virtualizados', 'Infraestructura', 'Finalizada',
 '[{"id":30,"nombre":"Gladys Muñoz"},{"id":28,"nombre":"Andreina Medina"}]',
 '2026-05-01 09:30:00', '2026-05-02 12:00:00', 72),

('Configuración de reglas de firewall para nueva zona DMZ', 'Redes', 'Finalizada',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":25,"nombre":"Yedcibel Diaz"}]',
 '2026-05-03 10:00:00', '2026-05-05 16:00:00', 72),

('Actualización de paquetes npm y composer en proyectos activos', 'Desarrollo', 'Finalizada',
 '[{"id":24,"nombre":"Jorgue Lazaro"}]',
 '2026-05-06 11:15:00', '2026-05-06 15:30:00', 74),

('Implementación de log centralizado con ELK Stack', 'Sistemas', 'Finalizada',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":31,"nombre":"Daniel Sanchez"}]',
 '2026-05-07 08:00:00', '2026-05-09 18:00:00', 72),

('Revisión de contratos de soporte técnico con proveedores', 'Soporte Técnico', 'Finalizada',
 '[{"id":29,"nombre":"Julio Acosta"},{"id":28,"nombre":"Andreina Medina"}]',
 '2026-05-08 09:00:00', '2026-05-08 15:00:00', 72),

('Migración de datos del sistema legacy al nuevo ERP', 'Base de Datos', 'Finalizada',
 '[{"id":31,"nombre":"Daniel Sanchez"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-05-09 08:30:00', '2026-05-13 17:00:00', 74),

('Configuración de Wake-on-LAN en red inalámbrica corporativa', 'Redes', 'Finalizada',
 '[{"id":26,"nombre":"Neri la Cruz"},{"id":22,"nombre":"Luis Ruiz"}]',
 '2026-05-11 14:00:00', '2026-05-12 11:00:00', 72),

('Plan de recuperación ante desastres para centro de datos', 'Infraestructura', 'Finalizada',
 '[{"id":30,"nombre":"Gladys Muñoz"},{"id":23,"nombre":"Edmundo Oberto"},{"id":31,"nombre":"Daniel Sanchez"}]',
 '2026-05-13 08:00:00', '2026-05-15 17:00:00', 72),

('Automatización de deploys con GitHub Actions para proyecto O.S.T.I.', 'Desarrollo', 'Finalizada',
 '[{"id":24,"nombre":"Jorgue Lazaro"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-05-14 10:00:00', '2026-05-16 14:30:00', 74),

-- ── 5 CANCELADAS ────────────────────────────────────────────
('Implementación de ERP en la nube para sede principal', 'Sistemas', 'Cancelada',
 '[{"id":23,"nombre":"Edmundo Oberto"},{"id":30,"nombre":"Gladys Muñoz"}]',
 '2026-03-01 09:00:00', '2026-03-10 17:00:00', 72),

('Compra e instalación de nuevo firewall Fortinet 200F', 'Redes', 'Cancelada',
 '[{"id":22,"nombre":"Luis Ruiz"},{"id":25,"nombre":"Yedcibel Diaz"},{"id":26,"nombre":"Neri la Cruz"}]',
 '2026-03-20 10:30:00', '2026-03-25 16:00:00', 72),

('Desarrollo de app móvil para consulta de inventario', 'Desarrollo', 'Cancelada',
 '[{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-04-10 13:00:00', '2026-04-15 15:00:00', 74),

('Actualización de cableado categoría 7 en todo el edificio', 'Infraestructura', 'Cancelada',
 '[{"id":28,"nombre":"Andreina Medina"},{"id":29,"nombre":"Julio Acosta"}]',
 '2026-05-01 08:00:00', '2026-05-05 17:00:00', 72),

('Integración con API de facturación electrónica del SAT', 'Desarrollo', 'Cancelada',
 '[{"id":24,"nombre":"Jorgue Lazaro"},{"id":40,"nombre":"Emmanuel Lujan"}]',
 '2026-05-10 09:00:00', '2026-05-14 18:00:00', 74);
