<?php


function esSuperAdmin(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return !empty($_SESSION['es_superadmin']);
}


function esAdmin(?string $nombreUsuario = null, ?string $rolUsuario = null): bool
{
    return esSuperAdmin();
}

/**
 * Normaliza un nombre para comparación interna.
 */
function normalizarNombre(string $nombre): string
{
    return mb_strtolower(trim($nombre), 'UTF-8');
}


function esNombreAdmin(string $nombreCompleto): bool
{
    $nombre = normalizarNombre($nombreCompleto);
    return $nombre === 'gladys' || str_starts_with($nombre, 'gladys ');
}

/**
 * Alias requerido por login.php al iniciar sesión.
 * Determina si el nombre completo corresponde al superadmin del sistema.
 */
function esNombreSuperAdmin(string $nombreCompleto): bool
{
    return esNombreAdmin($nombreCompleto);
}
?>
