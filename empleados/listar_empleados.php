<?php

session_start();

include '../includes/db.php'; // Salimos de carpeta

include '../includes/db_schema.php';



if (!isset($_SESSION['user_id'])) {

    http_response_code(401);

    header('Content-Type: application/json');

    echo json_encode(["error" => "Sesión vencida"]);

    exit;

}



asegurarColumnasEmpleados($conn);



$busqueda = trim($_GET['buscar'] ?? '');

$like = '%' . $busqueda . '%';



$query = "SELECT id, nombre, apellido, formacion, correo, telefono

          FROM empleados

          WHERE nombre LIKE ? OR apellido LIKE ?

          ORDER BY id DESC";

$stmt = $conn->prepare($query);

if (!$stmt) {

    header('Content-Type: application/json');

    echo json_encode(["error" => "Error de conexión al preparar consulta"]);

    exit;

}

$stmt->bind_param('ss', $like, $like);

$stmt->execute();

$result = $stmt->get_result();



// Verificación de seguridad para evitar que el JSON falle si la consulta da error

if (!$result) {

    die(json_encode(["error" => mysqli_error($conn)]));

}



$empleados = [];



while ($row = mysqli_fetch_assoc($result)) {

    // Aplicamos el filtro N/D para los campos opcionales

    $row['correo'] = !empty($row['correo']) ? $row['correo'] : 'N/D';

    $row['telefono'] = !empty($row['telefono']) ? $row['telefono'] : 'N/D';

    $empleados[] = $row;

}



// Enviamos el encabezado correcto para que el navegador sepa que es JSON

header('Content-Type: application/json');

echo json_encode($empleados);

?>