<?php
session_start();
date_default_timezone_set('America/Caracas');
include 'db.php';
include 'security.php';
require_once 'functions.php';

// Si no hay sesión, al login de una
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? 'tecnico';
$es_admin = esAdmin($nombre_usuario, $rol_usuario);
?>