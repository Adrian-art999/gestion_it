<?php
date_default_timezone_set('America/Caracas');
require_once '../includes/db.php';
require_once '../includes/activity_history.php';
require_once '../includes/db_schema.php';
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
$descripcion = trim($_POST['descripcion'] ?? '');
$estado = trim($_POST['estado'] ?? 'En progreso');
$fecha_input = trim($_POST['fecha'] ?? '');
$areaSeleccionada = trim($_POST['area'] ?? 'Informática');
$areaManual = trim($_POST['area_manual'] ?? '');
$area = ($areaSeleccionada === 'OTRA') ? $areaManual : $areaSeleccionada;

$zona = new DateTimeZone('America/Caracas');
$fechaInicioObj = new DateTime('now', $zona);
$fechaInicioObj->setTime(0, 0, 0); // Cero Horas

if ($fecha_input !== '') {
    $fechaBase = DateTime::createFromFormat('Y-m-d', $fecha_input, $zona);
    if ($fechaBase instanceof DateTime) {
        $fechaBase->setTime(0, 0, 0);
        
        if ($id_actividad === 0) {
            $hoy = new DateTime('today', $zona);
            $hoy->setTime(0, 0, 0);
            if ($fechaBase < $hoy) {
                $fechaBase = $hoy;
            }
        }
        $fechaInicioObj = $fechaBase;
    }
}
$fecha_inicio = $fechaInicioObj->format('Y-m-d H:i:s');
$fecha_solo_display = $fechaInicioObj->format('d-m-Y');

$id_usuario = (int) ($_SESSION['user_id'] ?? 1);
$usuarioNombre = (string) ($_SESSION['nombre'] ?? 'Sistema');

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
        
        $responsablesAntArray = json_decode($responsablesAnterior, true) ?: [];
        $responsablesNuevosArray = $data_final;
        
        $responsablesAntIds = array_map(function($r) { return $r['id']; }, $responsablesAntArray);
        $responsablesNuevosIds = array_map(function($r) { return $r['id']; }, $responsablesNuevosArray);
        
        $responsablesAñadidos = array_diff($responsablesNuevosIds, $responsablesAntIds);
        $responsablesEliminados = array_diff($responsablesAntIds, $responsablesNuevosIds);
        $hayCambiosResponsables = count($responsablesAñadidos) > 0 || count($responsablesEliminados) > 0;
        
        $sqlUpdate = "UPDATE actividades 
                      SET descripcion = ?, 
                          area = ?, 
                          estado = ?, 
                          fecha_inicio = ?, 
                          responsables_data = ? 
                      WHERE id = ?";
        $stmt = $conn->prepare($sqlUpdate);
        $stmt->bind_param('sssssi', $descripcion, $area, $estado, $fecha_inicio, $responsables_json, $id_actividad);
        $ok = $stmt->execute();

        if ($ok) {
            $cambios = [];
            
            if ($descripcion !== $descripcionAnterior) {
                $cambios[] = "actualizó la descripción";
            }
            
            if ($area !== $areaAnterior) {
                $cambios[] = "actualizó el campo Área";
            }
            
            if ($estado !== $estadoAnterior) {
                $cambios[] = "cambió el estado a {$estado}";
            }
            
            $fechaAnteriorSolo = substr((string) $fechaAnterior, 0, 10);
            $fechaInicioSolo   = substr($fecha_inicio, 0, 10);
            if ($fechaInicioSolo !== $fechaAnteriorSolo) {
                $cambios[] = "actualizó la fecha";
            }
            
            if ($hayCambiosResponsables) {
                if (count($responsablesAñadidos) > 0 && count($responsablesEliminados) === 0) {
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
    $sqlInsert = "INSERT INTO actividades (descripcion, area, estado, fecha_inicio, id_usuario, responsables_data) 
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sqlInsert);
    $stmt->bind_param('ssssis', $descripcion, $area, $estado, $fecha_inicio, $id_usuario, $responsables_json);
    $ok = $stmt->execute();
    $id_actividad = (int) $conn->insert_id;

    if ($ok && $id_actividad > 0) {
        registrarHistorialActividad(
            $conn,
            $id_actividad,
            'CREACION',
            "Actividad creada por {$usuarioNombre} el {$fecha_solo_display}",
            $id_usuario,
            $usuarioNombre
        );
    }
}

echo $ok ? 'success' : 'error';
?>