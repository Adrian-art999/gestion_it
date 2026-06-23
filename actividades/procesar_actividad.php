<?php
date_default_timezone_set('America/Caracas');
require_once '../includes/db.php';
require_once '../includes/activity_history.php';
require_once '../includes/db_schema.php';
require_once '../includes/functions.php';
require_once '../includes/permisos.php';
session_start();

asegurarEstadoActividades($conn);

if (!isset($_SESSION['user_id'])) {
    echo 'Sesión vencida';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo 'Método no permitido';
    exit;
}

$id_actividad = isset($_POST['id_actividad']) ? (int) $_POST['id_actividad'] : 0;

// Permisos: editar actividad requiere actividades_editar
if (!tienePermiso('actividades_editar')) {
    echo 'No tienes permiso para esta acción';
    exit;
}

$descripcion = trim($_POST['descripcion'] ?? '');
// ── Validación de descripción ──
$erroresDesc = [];
if (preg_match('/^\d+$/', $descripcion)) {
    $erroresDesc[] = 'No puede contener únicamente números';
}
if (preg_match('/^\d/', $descripcion)) {
    $erroresDesc[] = 'No puede empezar con un número';
}
if (mb_strlen($descripcion) < 15) {
    $erroresDesc[] = 'Debe tener al menos 15 caracteres';
}
if (!empty($erroresDesc)) {
    echo 'Descripción inválida: ' . implode('. ', $erroresDesc) . '.';
    exit;
}
$estado = trim($_POST['estado'] ?? 'En progreso');
$fecha_input = trim($_POST['fecha'] ?? '');
$areaSeleccionada = trim($_POST['area'] ?? 'Informática');
$areaManual = trim($_POST['area_manual'] ?? '');
$area = ($areaSeleccionada === 'OTRA') ? $areaManual : $areaSeleccionada;

$zona = new DateTimeZone('America/Caracas');
// Capturar fecha y hora exacta del servidor (incluye horas y minutos)
$fechaInicioObj = new DateTime('now', $zona);

if ($fecha_input !== '') {
    $fechaBase = DateTime::createFromFormat('Y-m-d', $fecha_input, $zona);
    if ($fechaBase instanceof DateTime) {
        // Si el usuario eligió una fecha distinta a hoy, conservar las horas actuales
        // pero anclar al inicio del día seleccionado solo si es edición de fecha pasada;
        // para nuevas actividades o la fecha de hoy: capturar la hora exacta del servidor.
        $hoy = (new DateTime('now', $zona))->format('Y-m-d');
        $fechaSeleccionada = $fechaBase->format('Y-m-d');

        if ($fechaSeleccionada === $hoy) {
            // Fecha de hoy → conservar hora y minutos actuales del servidor
            $fechaBase->setTime(
                (int) $fechaInicioObj->format('H'),
                (int) $fechaInicioObj->format('i'),
                0
            );
        } else {
            // Fecha futura o pasada seleccionada manualmente → inicio del día (00:00)
            $fechaBase->setTime(0, 0, 0);
        }

        if ($id_actividad === 0) {
            // Para nuevas actividades, no permitir fechas anteriores a hoy
            $hoyObj = new DateTime('today', $zona);
            if ($fechaBase < $hoyObj) {
                $fechaBase = $fechaInicioObj; // Usar fecha/hora actuales completas
            }
        }
        $fechaInicioObj = $fechaBase;
    }
}
$fecha_inicio = $fechaInicioObj->format('Y-m-d H:i:s');

$id_usuario = (int) ($_SESSION['user_id'] ?? 1);
$usuarioNombre = (string) ($_SESSION['nombre'] ?? 'Sistema');

$raw_ids = $_POST['responsable_id'] ?? [];
if (!is_array($raw_ids)) {
    $raw_ids = [];
}
$data_final = [];

$stmtEmpleado = $conn->prepare("SELECT id, nombre, apellido FROM empleados WHERE id = ? LIMIT 1");
$empId = 0;
if ($stmtEmpleado) {
    $stmtEmpleado->bind_param('i', $empId);
    foreach ($raw_ids as $id) {
        $empId = (int) $id;
        if ($empId <= 0) {
            continue;
        }
        $stmtEmpleado->execute();
        $res = $stmtEmpleado->get_result();
        if ($emp = $res->fetch_assoc()) {
            $nombreCompleto = trim(($emp['nombre'] ?? '') . ' ' . ($emp['apellido'] ?? ''));
            $data_final[] = ['id' => $empId, 'nombre' => $nombreCompleto];
        }
    }
}
if (empty($data_final)) {
    $data_final[] = ['id' => 0, 'nombre' => 'Sin asignar'];
}
$responsables_json = json_encode($data_final, JSON_UNESCAPED_UNICODE);

$ok = false;

if ($id_actividad > 0) {
    $sqlActual = "SELECT descripcion, area, estado, responsables_data, fecha_inicio FROM actividades WHERE id = ? LIMIT 1";
    $stmtActual = $conn->prepare($sqlActual);
    $stmtActual->bind_param('i', $id_actividad);
    $stmtActual->execute();
    $datosActuales = $stmtActual->get_result()->fetch_assoc();
    
    if ($datosActuales) {
        $descripcionAnterior = $datosActuales['descripcion'] ?? '';
        $areaAnterior = $datosActuales['area'] ?? '';
        $estadoAnterior = $datosActuales['estado'] ?? '';
        $responsablesAnterior = $datosActuales['responsables_data'] ?? '';
        $fechaAnterior = $datosActuales['fecha_inicio'] ?? '';

        // Usuario Común: solo puede cambiar descripción y responsables
        if (!tienePermiso('actividades_eliminar')) {
            $area = $areaAnterior;
            $estado = $estadoAnterior;
            $fecha_input = !empty($fechaAnterior) ? date('Y-m-d', strtotime($fechaAnterior)) : '';
        }
        
        $responsablesAntArray = json_decode($responsablesAnterior, true) ?: [];
        $responsablesNuevosArray = $data_final;
        
        $responsablesAntIds = array_map(function($r) { return $r['id']; }, $responsablesAntArray);
        $responsablesNuevosIds = array_map(function($r) { return $r['id']; }, $responsablesNuevosArray);
        
        $responsablesAñadidos = array_diff($responsablesNuevosIds, $responsablesAntIds);
        $responsablesEliminados = array_diff($responsablesAntIds, $responsablesNuevosIds);
        $hayCambiosResponsables = count($responsablesAñadidos) > 0 || count($responsablesEliminados) > 0;
        
        $estadoAnterior = $datosActuales['estado'] ?? '';
        $fecha_fin_valor = $datosActuales['fecha_fin'] ?? null;
        if ($estado === 'Finalizada' && $estadoAnterior !== 'Finalizada') {
            $fecha_fin_valor = date('Y-m-d H:i:s');
        } elseif ($estado !== 'Finalizada' && $estadoAnterior === 'Finalizada') {
            $fecha_fin_valor = null;
        }

        $sqlUpdate = "UPDATE actividades 
                      SET descripcion = ?, 
                          area = ?, 
                          estado = ?, 
                          fecha_inicio = ?, 
                          responsables_data = ?,
                          fecha_fin = ?
                      WHERE id = ?";
        $stmt = $conn->prepare($sqlUpdate);
        if (!$stmt) {
            error_log('Error prepare UPDATE actividad: ' . $conn->error);
            echo 'error';
            exit;
        }
        $stmt->bind_param('ssssssi', $descripcion, $area, $estado, $fecha_inicio, $responsables_json, $fecha_fin_valor, $id_actividad);
        $ok = $stmt->execute();

        if ($ok) {
            $cambios = [];
            $cambiosDetalle = [];
            
            if ($descripcion !== $descripcionAnterior) {
                $cambios[] = "cambió la descripción";
                $cambiosDetalle[] = "Descripción: '" . mb_substr($descripcionAnterior, 0, 80) . "' → '" . mb_substr($descripcion, 0, 80) . "'";
            }
            
            if ($area !== $areaAnterior) {
                $cambios[] = "cambió el área";
                $cambiosDetalle[] = "Área: '{$areaAnterior}' → '{$area}'";
            }
            
            if ($estado !== $estadoAnterior) {
                $cambios[] = "cambió el estado a {$estado}";
                $cambiosDetalle[] = "Estado: '{$estadoAnterior}' → '{$estado}'";
            }
            
            $fechaAnteriorSolo = substr((string) $fechaAnterior, 0, 10);
            $fechaInicioSolo   = substr($fecha_inicio, 0, 10);
            if ($fechaInicioSolo !== $fechaAnteriorSolo) {
                $cambios[] = "cambió la fecha";
                $cambiosDetalle[] = "Fecha: '{$fechaAnteriorSolo}' → '{$fechaInicioSolo}'";
            }
            
            if ($hayCambiosResponsables) {
                if (count($responsablesAñadidos) > 0 && count($responsablesEliminados) === 0) {
                    $cant = count($responsablesAñadidos);
                    $cambios[] = "añadió " . ($cant === 1 ? "un responsable" : "{$cant} responsables");
                    $cambiosDetalle[] = "Añadió responsable(s) ID: " . implode(', ', $responsablesAñadidos);
                } elseif (count($responsablesEliminados) > 0 && count($responsablesAñadidos) === 0) {
                    $cant = count($responsablesEliminados);
                    $cambios[] = "eliminó " . ($cant === 1 ? "un responsable" : "{$cant} responsables");
                    $cambiosDetalle[] = "Eliminó responsable(s) ID: " . implode(', ', $responsablesEliminados);
                } else {
                    $cambios[] = "modificó los responsables";
                    $cambiosDetalle[] = "Modificó responsables";
                }
            }
            
            $sufijo = "de la Actividad ID {$id_actividad}";
            $accionLog = construir_mensaje_cambios($cambios, $sufijo);
            $detalleLog = json_encode([
                'tipo' => 'actividad',
                'accion' => 'actualizacion',
                'actividad_id' => $id_actividad,
                'cambios' => $cambiosDetalle
            ], JSON_UNESCAPED_UNICODE);
            registrar_log($conn, $id_usuario, $accionLog . "... (Ver info)", $detalleLog);
            
            // Notificación Toast al frontend (actualización)
            $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Actividad actualizada correctamente!'];
            
            $mensajeHistorial = "El usuario {$usuarioNombre} actualizó la actividad";
            
            registrarHistorialActividad(
                $conn,
                $id_actividad,
                'ACTUALIZACION',
                $mensajeHistorial,
                $id_usuario,
                $usuarioNombre
            );
        }
    }
} else {
    asegurarColumnaFechaFin($conn);

    // Asegurar que la columna responsables_data existe antes de insertar
    $checkCol = $conn->query("SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'actividades' AND COLUMN_NAME = 'responsables_data' LIMIT 1");
    if ($checkCol && $checkCol->num_rows === 0) {
        $conn->query("ALTER TABLE actividades ADD COLUMN responsables_data LONGTEXT NULL AFTER estado");
    }

    // Si se crea directamente como Finalizada, asignar fecha_fin = fecha_inicio
    $fecha_fin_sql = ($estado === 'Finalizada') ? ", fecha_fin = '{$fecha_inicio}'" : "";

    $sqlInsert = "INSERT INTO actividades (descripcion, area, estado, fecha_inicio, id_usuario, responsables_data) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    if (!$stmt) {
        error_log('Error prepare INSERT actividad: ' . $conn->error);
        echo 'error';
        exit;
    }
    $stmt->bind_param('ssssis', $descripcion, $area, $estado, $fecha_inicio, $id_usuario, $responsables_json);
    $ok = $stmt->execute();
    $id_actividad = (int) $conn->insert_id;

    if (!empty($fecha_fin_sql) && $ok) {
        $conn->query("UPDATE actividades SET fecha_fin = '{$fecha_inicio}' WHERE id = {$id_actividad} LIMIT 1");
    }

    if ($ok && $id_actividad > 0) {
        $detalleLog = json_encode([
            'tipo' => 'actividad',
            'accion' => 'creacion',
            'actividad_id' => $id_actividad,
            'descripcion' => mb_substr($descripcion, 0, 80),
            'area' => $area,
            'estado' => $estado
        ], JSON_UNESCAPED_UNICODE);
        registrar_log($conn, $id_usuario, "Registró la actividad ID {$id_actividad}... (Ver info)", $detalleLog);
        registrarHistorialActividad(
            $conn,
            $id_actividad,
            'CREACION',
            "Actividad creada por {$usuarioNombre}",
            $id_usuario,
            $usuarioNombre
        );
        $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Actividad registrada con éxito!'];
    } else {
        error_log('Error INSERT actividad: ' . ($stmt->error ?? 'unknown'));
    }
}

echo $ok ? 'success' : 'error';
?>