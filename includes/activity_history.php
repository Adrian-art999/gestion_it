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
    asegurarTablaHistorialActividades($conn);
    $usuarioIdSafe = $usuarioId ?? 0;
    $sql = "INSERT INTO actividad_historial (actividad_id, accion, detalle, usuario_id, usuario_nombre)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issis', $actividadId, $accion, $detalle, $usuarioIdSafe, $usuarioNombre);
    $stmt->execute();
}
?>
