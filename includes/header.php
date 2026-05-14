<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$nombre_usuario = $_SESSION['nombre'] ?? 'Usuario';
$rol_usuario = $_SESSION['rol'] ?? 'tecnico';
// $es_gladys_admin comes from config.php usually, let's make sure it's defined
if (!isset($es_admin)) {
    $es_admin = function_exists('esAdmin') ? esAdmin($nombre_usuario, $rol_usuario) : false;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System O.S.T.I</title>
    <script>
        
        window.ES_ADMIN = <?php echo $es_admin ? 'true' : 'false'; ?>;
    </script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* RESET Y BASE */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }
        body { display: flex; height: 100vh; background-color: #fff; color: #3c4043; overflow: hidden; }

        /* SIDEBAR */
        .sidebar { width: 186px; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 8px 7px; background: #fff; flex-shrink: 0; }
        .logo { font-size: 15px; color: #1a73e8; display: flex; align-items: center; gap: 7px; font-weight: 600; margin-bottom: 10px; padding-left: 5px; }
        .btn-nueva { background-color: #c2e7ff; color: #001d35; border: none; padding: 9px 12px; border-radius: 18px; font-weight: 500; display: flex; align-items: center; gap: 7px; cursor: pointer; margin-bottom: 12px; font-size: 12px; transition: 0.2s; }
        .btn-nueva:hover { background-color: #b3d7ef; }

        .menu-label { font-size: 10px; font-weight: bold; color: #70757a; margin: 10px 0 5px 7px; text-transform: uppercase; letter-spacing: 0.5px; }
        .menu-item { display: flex; align-items: center; gap: 8px; padding: 7px 9px; color: #444746; text-decoration: none; border-radius: 12px; font-size: 12px; margin-bottom: 2px; }
        .menu-item.active { background-color: #d3e3fd; color: #0b57d0; font-weight: 500; }
        .menu-item .material-icons { font-size: 16px; min-width: 16px; text-align: center; }
        .menu-item-sub { padding-left: 12px; }
        .menu-count {
            margin-left: auto;
            font-size: 11px;
            font-weight: 600;
            color: #5f6368;
            background: #f1f3f4;
            border-radius: 999px;
            min-width: 22px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }

        /* CONTENIDO PRINCIPAL */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; min-height: 0; }
        .header-top { padding: 10px 20px; display: flex; justify-content: space-between; align-items: center; }
        
        .toolbar { padding: 0 20px 8px 20px; display: flex; gap: 12px; align-items: center; }
        .search-container { position: relative; width: 250px; }
        .search-container span { position: absolute; left: 15px; top: 10px; color: #5f6368; }
        .search-container input { width: 100%; padding: 10px 15px 10px 45px; border-radius: 20px; border: 1px solid #dadce0; background: #f1f3f4; outline: none; }
        .filter-select { flex-grow: 1; padding: 10px 15px; border-radius: 20px; border: 1px solid #dadce0; background: white; cursor: pointer; }

        /* TABLA */
        .tabla-container { padding: 0 20px; width: 100%; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px 10px; font-size: 13px; color: #3c4043; border-bottom: 2px solid #f1f3f4; }
        td { padding: 15px 10px; font-size: 14px; border-bottom: 1px solid #f1f3f4; }
        tbody tr { transition: background-color 0.18s ease; }
        tbody tr:hover { background: #f8fafd; }
        .cell-area { width: 160px; min-width: 140px; max-width: 200px; }
        .cell-description { max-width: 340px; }
        .cell-truncate {
            display: inline-block;
            max-width: calc(100% - 38px);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            vertical-align: middle;
        }

        /* Columna de acciones y menú de 3 puntos */
        th:last-child, td:last-child {
            width: 86px;
            min-width: 86px;
            text-align: right;
            position: relative;
            overflow: visible;
        }
        .opciones-menu { position: relative; display: inline-block; }
        .btn-opciones {
            cursor: pointer;
            color: #5f6368;
            border-radius: 50%;
            padding: 6px;
            user-select: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s;
        }
        .btn-opciones:hover { background-color: #eceff1; color: #202124; }
        .btn-opciones:focus-visible {
            outline: 2px solid #1a73e8;
            outline-offset: 1px;
        }
        .dropdown-opciones {
            display: none;
            position: absolute;
            right: 0;
            top: 34px;
            min-width: 150px;
            background: #fff;
            border: 1px solid #dadce0;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
            z-index: 5000;
            padding: 4px 0;
        }
        .dropdown-opciones.show { display: block; }
        .dropdown-opciones button {
            width: 100%;
            border: none;
            background: none;
            padding: 10px 14px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            color: #3c4043;
        }
        .dropdown-opciones button:hover { background-color: #f1f3f4; }
        .dropdown-opciones button:focus-visible {
            outline: none;
            background-color: #e8f0fe;
        }
        .btn-accion-actividad-finalizar {
            border: none;
            background: transparent;
            cursor: pointer;
            padding: 6px;
            border-radius: 999px;
            vertical-align: middle;
            margin-right: 2px;
            color: #1e8e3e;
        }
        .btn-accion-actividad-finalizar:hover { background: #e6f4ea; }
        .btn-accion-actividad-finalizar .material-icons { font-size: 20px; }
        .btn-responsables-toggle,
        .btn-descripcion-expandir {
            border: none;
            background: #e8f0fe;
            color: #1a73e8;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            padding: 3px 8px;
            cursor: pointer;
        }
        .btn-descripcion-expandir {
            margin-left: 8px;
            min-width: 24px;
            padding: 3px 0;
            text-align: center;
        }
        .responsables-popover {
            display: none;
            position: absolute;
            left: 0;
            margin-top: 8px;
            background: #fff;
            border: 1px solid #dadce0;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0,0,0,.14);
            padding: 10px;
            z-index: 100;
            min-width: 180px;
        }
        .responsables-popover.abierto { display: block; }
        .responsables-cell { position: relative; }
        /* MODALES - CORREGIDOS PARA SCROLL Y X */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .modal-content { 
            background: white; width: 620px; margin: 5vh auto; padding: 35px; border-radius: 28px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); position: relative; 
            max-height: 85vh; overflow-y: auto; 
        }
        .btn-close-modal { position: absolute; top: 20px; right: 20px; cursor: pointer; color: #5f6368; }
        
        .modal-content label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 500; color: #3c4043; margin-top: 15px; }
        .modal-content select, .modal-content input, .modal-content textarea { width: 100%; padding: 12px; border: 1px solid #dadce0; border-radius: 8px; font-size: 14px; outline: none; transition: 0.2s; }
        .modal-content select:focus, .modal-content input:focus { border: 2px solid #1a73e8; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .responsable-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .btn-remove { color: #d93025; cursor: pointer; display: flex; align-items: center; }

        .btn-save { background: #1a73e8; color: white; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 600; cursor: pointer; }
        .btn-cancel { background: #f1f3f4; color: #3c4043; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 600; cursor: pointer; }

        /* STATUS PILLS */
        .status-pill { padding: 4px 12px; border-radius: 15px; font-size: 12px; font-weight: 500; }
        .status-progreso { background: #fff4e5; color: #b06000; }
        .status-finalizada { background: #e6f4ea; color: #1e8e3e; }
        .status-cancelada { background: #fce8e6; color: #d93025; }

        .paginacion-actividades {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px 20px;
            height: 60px;
            min-height: 60px;
            max-height: 60px;
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            position: sticky;
            bottom: 0;
            background: #fff;
            border-top: 1px solid #e8eaed;
            z-index: 40;
        }
        .pagina-cuadro {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            border: 1px solid #dadce0;
            border-radius: 8px;
            color: #3c4043;
            text-decoration: none;
            font-size: 14px;
            background: #fff;
            transition: all 0.2s;
        }
        .pagina-cuadro:hover {
            border-color: #1a73e8;
            color: #1a73e8;
            background: #f5f9ff;
        }
        .pagina-cuadro.activa {
            background: #1a73e8;
            color: #fff;
            border-color: #1a73e8;
            font-weight: 600;
        }
        .mensaje-vacio {
            text-align: center !important;
            color: #70757a;
            padding: 34px 16px !important;
            vertical-align: middle;
        }
        .dashboard-top-actions {
            padding: 8px 20px 0 20px;
            display: flex;
            justify-content: flex-end;
        }
        .btn-reporte-pdf {
            border: 1px solid #dadce0;
            background: #fff;
            padding: 7px 14px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            transition: all 0.2s ease;
        }
        .btn-reporte-pdf:hover { border-color: #1a73e8; color: #1a73e8; background: #f8fbff; }
        .dashboard-greeting {
            padding: 3px 20px 2px 20px;
            color: #3c4043;
            font-size: 17px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .dashboard-toolbar { padding: 2px 20px 6px 20px; }
        .dashboard-search { flex-grow: 1; position: relative; display: flex; align-items: center; width: auto; }
        .dashboard-search .material-icons { position: absolute; left: 12px; color: #5f6368; font-size: 19px; }
        .dashboard-search-input { padding-left: 40px !important; background: #f1f3f4 !important; border: none !important; height: 42px; border-radius: 10px !important; }
        .dashboard-filter-select { width: 190px; height: 42px; background: #f1f3f4; border: none; border-radius: 10px; }

        /* Panel reutilizable para listas flotantes */
        .panel-lista {
            width: 70%;
            max-width: 900px;
            margin: 22px auto;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
            padding: 24px;
        }
        .panel-lista .titulo-panel {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            color: #3c4043;
            font-size: 28px;
            font-weight: 500;
        }
        .panel-lista .buscador-panel {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #dadce0;
            outline: none;
            background: #f8f9fa;
            margin-bottom: 18px;
        }

        /* Scrollbar modal */
        .modal-content::-webkit-scrollbar { width: 8px; }
        .modal-content::-webkit-scrollbar-track { background: #f1f3f4; border-radius: 10px; }
        .modal-content::-webkit-scrollbar-thumb { background: #dadce0; border-radius: 10px; }
    </style>
    <link rel="stylesheet" href="assets/css/professional-ux.css">
    <link rel="stylesheet" href="assets/css/glassmorphism-dashboard.css">
</head>
<body>
<div id="popover" style="display:none; position:absolute; background:#323336; color:white; padding:10px; border-radius:8px; z-index:2000; font-size: 13px;"></div>