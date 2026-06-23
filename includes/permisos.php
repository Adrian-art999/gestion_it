<?php
/**
 * Permisos Granulares — Sistema O.S.T.I.
 *
 * Almacena los permisos en $_SESSION['permisos'] como un array asociativo.
 * Superadmin (Gladys) tiene todos los permisos en true siempre.
 * El permiso 'super_admin' concede control total absoluto sobre el sistema.
 */

if (!function_exists('listaPermisos')) {
    /**
     * Lista maestra de todos los permisos disponibles.
     */
    function listaPermisos(): array
    {
        return [
            'actividades_editar',
            'actividades_eliminar',
            'actividades_finalizar',
            'actividades_info',
            'empleados_listar',
            'empleados_registrar',
            'empleados_editar',
            'empleados_eliminar',
            'empleados_info',
            'usuarios_listar',
            'usuarios_registrar',
            'usuarios_editar',
            'usuarios_eliminar',
            'reportes_pdf',
            'bitacora',
            'super_admin',
        ];
    }
}

if (!function_exists('permisosDefault')) {
    /**
     * Permisos por defecto para un usuario según su rol.
     */
    function permisosDefault(string $rol): array
    {
        $todos = listaPermisos();

        if ($rol === 'admin') {
            // Admin clásico: todo true (backward compatibility)
            return array_combine($todos, array_fill(0, count($todos), true));
        }

        // Usuario Común (tecnico) — solo lo esencial
        return [
            'actividades_editar'   => true,
            'actividades_eliminar' => false,
            'actividades_finalizar'=> true,
            'actividades_info'     => true,
            'empleados_listar'     => true,
            'empleados_registrar'  => false,
            'empleados_editar'     => false,
            'empleados_eliminar'   => false,
            'empleados_info'       => false,
            'usuarios_listar'      => false,
            'usuarios_registrar'   => false,
            'usuarios_editar'      => false,
            'usuarios_eliminar'    => false,
            'reportes_pdf'         => false,
            'bitacora'             => false,
            'super_admin'          => false,
        ];
    }
}

if (!function_exists('asegurarColumnaPermisos')) {
    /**
     * Crea la columna permisos si no existe.
     */
    function asegurarColumnaPermisos(mysqli $conn): void
    {
        static $hecho = false;
        if ($hecho) return;

        $sql = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = 'usuarios'
                  AND COLUMN_NAME = 'permisos'
                LIMIT 1";
        $res = $conn->query($sql);
        if ($res && $res->fetch_row()) {
            $hecho = true;
            return;
        }

        $conn->query("ALTER TABLE usuarios
                      ADD COLUMN permisos TEXT DEFAULT NULL
                      COMMENT 'JSON permisos granulares'
                      AFTER recuperacion_actualizado_en");

        // Asignar defaults a usuarios existentes
        $conn->query("UPDATE usuarios
                      SET permisos = '"
                      . json_encode(permisosDefault('admin'), JSON_UNESCAPED_UNICODE)
                      . "' WHERE rol = 'admin' AND permisos IS NULL");

        $conn->query("UPDATE usuarios
                      SET permisos = '"
                      . json_encode(permisosDefault('tecnico'), JSON_UNESCAPED_UNICODE)
                      . "' WHERE rol = 'tecnico' AND permisos IS NULL");

        $hecho = true;
    }
}

if (!function_exists('cargarPermisosEnSesion')) {
    /**
     * Carga/recarga los permisos del usuario actual en $_SESSION.
     * Si es Superadmin por nombre, se saltan los permisos de BD.
     */
    function cargarPermisosEnSesion(mysqli $conn, int $userId, string $nombreCompleto, string $rol): void
    {
        // Superadmin (Gladys) tiene todo
        if (esNombreSuperAdmin($nombreCompleto)) {
            $_SESSION['permisos'] = permisosDefault('admin');
            $_SESSION['es_superadmin'] = true;
            return;
        }

        $_SESSION['es_superadmin'] = false;

        $permisos = permisosDefault($rol); // fallback

        $stmt = $conn->prepare("SELECT permisos FROM usuarios WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            if ($row && !empty($row['permisos'])) {
                $decoded = json_decode($row['permisos'], true);
                if (is_array($decoded)) {
                    // Migración: mapear roles_gestionar antiguo → super_admin
                    if (isset($decoded['roles_gestionar'])) {
                        $decoded['super_admin'] = $decoded['roles_gestionar'];
                        unset($decoded['roles_gestionar']);
                    }
                    $permisos = array_merge($permisos, $decoded);
                }
            }
            $stmt->close();
        }

        // Si tiene el permiso super_admin, activar bypass total
        if (!empty($permisos['super_admin'])) {
            $_SESSION['es_superadmin'] = true;
        }

        $_SESSION['permisos'] = $permisos;
    }
}

if (!function_exists('tienePermiso')) {
    /**
     * Verifica si el usuario en sesión tiene un permiso específico.
     * Superadmin (Gladys) o poseedor del permiso 'super_admin' siempre tiene true.
     */
    function tienePermiso(string $permiso): bool
    {
        if (!empty($_SESSION['es_superadmin'])) {
            return true;
        }
        return !empty($_SESSION['permisos'][$permiso]);
    }
}

if (!function_exists('requierePermiso')) {
    /**
     * Detiene la ejecución con 403 si el usuario NO tiene el permiso.
     * Útil para endpoints de acciones (eliminar, editar, etc).
     */
    function requierePermiso(string $permiso): void
    {
        if (!tienePermiso($permiso)) {
            http_response_code(403);
            $esJson = !empty($_SERVER['HTTP_ACCEPT']) && str_contains($_SERVER['HTTP_ACCEPT'], 'application/json');
            if ($esJson || str_ends_with($_SERVER['SCRIPT_NAME'] ?? '', '.php')) {
                echo json_encode(['ok' => false, 'message' => 'No tienes permiso para esta acción']);
            } else {
                echo 'No tienes permiso para esta acción.';
            }
            exit;
        }
    }
}
