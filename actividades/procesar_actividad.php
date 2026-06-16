<?php
date_default_timezone_set('America/Caracas');
require_once '../includes/db.php';
require_once '../includes/activity_history.php';
require_once '../includes/db_schema.php';
<<<<<<< HEAD
require_once '../includes/functions.php';
require_once '../includes/permisos.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
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
<<<<<<< HEAD

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
=======
$descripcion = trim($_POST['descripcion'] ?? '');
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
$estado = trim($_POST['estado'] ?? 'En progreso');
$fecha_input = trim($_POST['fecha'] ?? '');
$areaSeleccionada = trim($_POST['area'] ?? 'Informática');
$areaManual = trim($_POST['area_manual'] ?? '');
$area = ($areaSeleccionada === 'OTRA') ? $areaManual : $areaSeleccionada;

$zona = new DateTimeZone('America/Caracas');
<<<<<<< HEAD
// Capturar fecha y hora exacta del servidor (incluye horas y minutos)
$fechaInicioObj = new DateTime('now', $zona);
=======
$fechaInicioObj = new DateTime('now', $zona);
$fechaInicioObj->setTime(0, 0, 0); // Cero Horas
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

if ($fecha_input !== '') {
    $fechaBase = DateTime::createFromFormat('Y-m-d', $fecha_input, $zona);
    if ($fechaBase instanceof DateTime) {
<<<<<<< HEAD
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
=======
        $fechaBase->setTime(0, 0, 0);
        
        if ($id_actividad === 0) {
            $hoy = new DateTime('today', $zona);
            $hoy->setTime(0, 0, 0);
            if ($fechaBase < $hoy) {
                $fechaBase = $hoy;
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
            }
        }
        $fechaInicioObj = $fechaBase;
    }
}
$fecha_inicio = $fechaInicioObj->format('Y-m-d H:i:s');
<<<<<<< HEAD
=======
$fecha_solo_display = $fechaInicioObj->format('d-m-Y');
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

$id_usuario = (int) ($_SESSION['user_id'] ?? 1);
$usuarioNombre = (string) ($_SESSION['nombre'] ?? 'Sistema');

<<<<<<< HEAD
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
=======
$responsables_ids = $_POST['responsable_id'] ?? [];
$data_final = [];

$stmtEmpleado = $conn->prepare("SELECT id, nombre, apellido FROM empleados WHERE id = ? LIMIT 1");
foreach ($responsables_ids as $id) {
    $empId = (int) $id;
    if ($empId <= 0) {
        continue;
    }
    $stmtEmpleado->bind_param('i', $empId);
    $stmtEmpleado->execute();
    $res = $stmtEmpleado->get_result();
    if ($emp = $res->fetch_assoc()) {
        $nombreCompleto = trim(($emp['nombre'] ?? '') . ' ' . ($emp['apellido'] ?? ''));
        $data_final[] = ['id' => $empId, 'nombre' => $nombreCompleto];
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
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
<<<<<<< HEAD

        // Usuario Común: solo puede cambiar descripción y responsables
        if (!tienePermiso('actividades_eliminar')) {
            $area = $areaAnterior;
            $estado = $estadoAnterior;
            $fecha_input = !empty($fechaAnterior) ? date('Y-m-d', strtotime($fechaAnterior)) : '';
        }
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
        
        $responsablesAntArray = json_decode($responsablesAnterior, true) ?: [];
        $responsablesNuevosArray = $data_final;
        
        $responsablesAntIds = array_map(function($r) { return $r['id']; }, $responsablesAntArray);
        $responsablesNuevosIds = array_map(function($r) { return $r['id']; }, $responsablesNuevosArray);
        
        $responsablesAñadidos = array_diff($responsablesNuevosIds, $responsablesAntIds);
        $responsablesEliminados = array_diff($responsablesAntIds, $responsablesNuevosIds);
        $hayCambiosResponsables = count($responsablesAñadidos) > 0 || count($responsablesEliminados) > 0;
        
<<<<<<< HEAD
        $estadoAnterior = $datosActuales['estado'] ?? '';
        $fecha_fin_valor = $datosActuales['fecha_fin'] ?? null;
        if ($estado === 'Finalizada' && $estadoAnterior !== 'Finalizada') {
            $fecha_fin_valor = date('Y-m-d H:i:s');
        } elseif ($estado !== 'Finalizada' && $estadoAnterior === 'Finalizada') {
            $fecha_fin_valor = null;
        }

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
        $sqlUpdate = "UPDATE actividades 
                      SET descripcion = ?, 
                          area = ?, 
                          estado = ?, 
                          fecha_inicio = ?, 
<<<<<<< HEAD
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
=======
                          responsables_data = ? 
                      WHERE id = ?";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bind_param('sssssi', $descripcion, $area, $estado, $fecha_inicio, $responsables_json, $id_actividad);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
        $ok = $stmt->execute();

        if ($ok) {
            $cambios = [];
<<<<<<< HEAD
            $accionLog = '';
            
            if ($descripcion !== $descripcionAnterior) {
                $cambios[] = "cambió la descripción";
            }
            
            if ($area !== $areaAnterior) {
                $cambios[] = "cambió el área";
=======
            
            if ($descripcion !== $descripcionAnterior) {
                $cambios[] = "actualizó la descripción";
            }
            
            if ($area !== $areaAnterior) {
                $cambios[] = "actualizó el campo Área";
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
            }
            
            if ($estado !== $estadoAnterior) {
                $cambios[] = "cambió el estado a {$estado}";
            }
            
            $fechaAnteriorSolo = substr((string) $fechaAnterior, 0, 10);
            $fechaInicioSolo   = substr($fecha_inicio, 0, 10);
            if ($fechaInicioSolo !== $fechaAnteriorSolo) {
<<<<<<< HEAD
                $cambios[] = "cambió la fecha";
=======
                $cambios[] = "actualizó la fecha";
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
            }
            
            if ($hayCambiosResponsables) {
                if (count($responsablesAñadidos) > 0 && count($responsablesEliminados) === 0) {
<<<<<<< HEAD
                    $cant = count($responsablesAñadidos);
                    $cambios[] = "añadió " . ($cant === 1 ? "un responsable" : "{$cant} responsables");
                } elseif (count($responsablesEliminados) > 0 && count($responsablesAñadidos) === 0) {
                    $cant = count($responsablesEliminados);
                    $cambios[] = "eliminó " . ($cant === 1 ? "un responsable" : "{$cant} responsables");
                } else {
                    $cambios[] = "modificó los responsables";
                }
            }
            
            $sufijo = "de la actividad ID {$id_actividad}";
            $accionLog = construir_mensaje_cambios($cambios, $sufijo);
            registrar_log($conn, $id_usuario, $accionLog);
            
            // Notificación Toast al frontend (actualización)
            $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Actividad actualizada correctamente!'];
            
            $mensajeHistorial = "El usuario {$usuarioNombre} actualizó la actividad";
=======
                    if (count($responsablesAñadidos) === 1) {
                        $cambios[] = "añadió un nuevo responsable";
                    } else {
                        $cambios[] = "añadió nuevos responsables";
                    }
                } elseif (count($responsablesEliminados) > 0 && count($responsablesAñadidos) === 0) {
                    if (count($responsablesEliminados) === 1) {
                        $cambios[] = "eliminó un responsable";
                    } else {
                        $cambios[] = "eliminó responsables";
                    }
                } else {
                    $cambios[] = "actualizó los responsables";
                }
            }
            
            if (empty($cambios)) {
                $mensajeHistorial = "El usuario {$usuarioNombre} actualizó la actividad";
            } else {
                if (count($cambios) === 1) {
                    $mensajeHistorial = "El usuario {$usuarioNombre} {$cambios[0]}";
                } elseif (count($cambios) === 2) {
                    $mensajeHistorial = "El usuario {$usuarioNombre} {$cambios[0]} y {$cambios[1]}";
                } else {
                    $ultimo = array_pop($cambios);
                    $mensajeHistorial = "El usuario {$usuarioNombre} " . implode(', ', $cambios) . " y {$ultimo}";
                }
            }
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
            
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
<<<<<<< HEAD
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
=======
    $sqlInsert = "INSERT INTO actividades (descripcion, area, estado, fecha_inicio, id_usuario, responsables_data) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    $stmt->bind_param('ssssis', $descripcion, $area, $estado, $fecha_inicio, $id_usuario, $responsables_json);
    $ok = $stmt->execute();
    $id_actividad = (int) $conn->insert_id;

<<<<<<< HEAD
    if (!empty($fecha_fin_sql) && $ok) {
        $conn->query("UPDATE actividades SET fecha_fin = '{$fecha_inicio}' WHERE id = {$id_actividad} LIMIT 1");
    }

    if ($ok && $id_actividad > 0) {
        registrar_log($conn, $id_usuario, "Registró la actividad ID {$id_actividad}");
=======
    if ($ok && $id_actividad > 0) {
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
        registrarHistorialActividad(
            $conn,
            $id_actividad,
            'CREACION',
<<<<<<< HEAD
            "Actividad creada por {$usuarioNombre}",
            $id_usuario,
            $usuarioNombre
        );
        $_SESSION['toast'] = ['tipo' => 'success', 'mensaje' => '¡Actividad registrada con éxito!'];
    } else {
        error_log('Error INSERT actividad: ' . ($stmt->error ?? 'unknown'));
=======
            "Actividad creada por {$usuarioNombre} el {$fecha_solo_display}",
            $id_usuario,
            $usuarioNombre
        );
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    }
}

echo $ok ? 'success' : 'error';
?>