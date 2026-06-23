<?php
session_start();
date_default_timezone_set('America/Caracas');
include 'db.php';
include 'security.php';
require_once 'functions.php';
require_once 'permisos.php';

// Si no hay sesión, al login de una
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? 'tecnico';
$es_admin = esAdmin($nombre_usuario, $rol_usuario);


// Recargar permisos si no están en sesión (primera carga después del login)
// o si la sesión tiene datos obsoletos (falta super_admin)
$permisosStale = isset($_SESSION['permisos']) && !array_key_exists('super_admin', $_SESSION['permisos']);
if ($permisosStale || (!isset($_SESSION['permisos']) && isset($conn))) {
    cargarPermisosEnSesion($conn, (int) $_SESSION['user_id'], $_SESSION['nombre'], $_SESSION['rol']);
}
?>