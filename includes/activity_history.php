<?php
require_once __DIR__ . '/db_schema.php';

function registrarHistorialActividad(
    mysqli $conn,
    int $actividadId,
    string $accion,
    string $detalle,
    ?int $usuarioId,
    ?string $usuarioNombre
): void {
<<<<<<< HEAD
    date_default_timezone_set('America/Caracas');
    asegurarTablaHistorialActividades($conn);
    $usuarioIdSafe = $usuarioId ?? 0;
    $ahora = date('Y-m-d H:i:s');
    $sql = "INSERT INTO actividad_historial (actividad_id, accion, detalle, usuario_id, usuario_nombre, creado_en)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ississ', $actividadId, $accion, $detalle, $usuarioIdSafe, $usuarioNombre, $ahora);
=======
    asegurarTablaHistorialActividades($conn);
    $usuarioIdSafe = $usuarioId ?? 0;
    $sql = "INSERT INTO actividad_historial (actividad_id, accion, detalle, usuario_id, usuario_nombre)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issis', $actividadId, $accion, $detalle, $usuarioIdSafe, $usuarioNombre);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    $stmt->execute();
}
?>
