<?php
session_start();
date_default_timezone_set('America/Caracas');
include 'db.php';
include 'security.php';
require_once 'functions.php';
<<<<<<< HEAD
require_once 'permisos.php';
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938

// Si no hay sesión, al login de una
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? 'tecnico';
$es_admin = esAdmin($nombre_usuario, $rol_usuario);
<<<<<<< HEAD

// Recargar permisos si no están en sesión (primera carga después del login)
if (!isset($_SESSION['permisos']) && isset($conn)) {
    cargarPermisosEnSesion($conn, (int) $_SESSION['user_id'], $_SESSION['nombre'], $_SESSION['rol']);
}
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
?>