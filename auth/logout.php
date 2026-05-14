<?php
session_start();
session_unset(); // Borra las variables
session_destroy(); // Destruye la sesión
header("Location: login.php"); // Manda al login directamente
exit();
?>