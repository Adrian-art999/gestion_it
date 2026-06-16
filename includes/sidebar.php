<aside class="sidebar">
    <div class="logo">
        <span class="material-icons">dns</span> System O.S.T.I
    </div>
    
    <button class="btn-nueva" onclick="abrirModal('modalActividad')">
        <span class="material-icons">add</span> Nueva actividad
    </button>

    <div class="menu-label">ACTIVIDADES</div>
    <?php
    if (!isset($conteo_todas, $conteo_en_progreso, $conteo_finalizadas, $conteo_canceladas) && isset($conn) && $conn instanceof mysqli) {
        $sqlConteoSidebar = "SELECT
                                COUNT(*) AS todas,
                                SUM(CASE WHEN estado = 'En progreso' THEN 1 ELSE 0 END) AS en_progreso,
                                SUM(CASE WHEN estado = 'Finalizada' THEN 1 ELSE 0 END) AS finalizadas,
                                SUM(CASE WHEN estado = 'Cancelada' THEN 1 ELSE 0 END) AS canceladas
                            FROM actividades";
        $resConteoSidebar = $conn->query($sqlConteoSidebar);
        $filaSidebar = $resConteoSidebar ? $resConteoSidebar->fetch_assoc() : [];
        $conteo_todas = (int) ($filaSidebar['todas'] ?? 0);
        $conteo_en_progreso = (int) ($filaSidebar['en_progreso'] ?? 0);
        $conteo_finalizadas = (int) ($filaSidebar['finalizadas'] ?? 0);
        $conteo_canceladas = (int) ($filaSidebar['canceladas'] ?? 0);
    }

    $estado_actual_sidebar = $filtro_sidebar ?? 'todos';
    $activo_todos = $estado_actual_sidebar === 'todos' ? 'active' : '';
    $activo_progreso = $estado_actual_sidebar === 'en_progreso' ? 'active' : '';
    $activo_finalizadas = $estado_actual_sidebar === 'finalizadas' ? 'active' : '';
    $activo_canceladas = $estado_actual_sidebar === 'canceladas' ? 'active' : '';

    $total_todas = (int) ($conteo_todas ?? 0);
    $total_progreso = (int) ($conteo_en_progreso ?? 0);
    $total_finalizadas = (int) ($conteo_finalizadas ?? 0);
    $total_canceladas = (int) ($conteo_canceladas ?? 0);
    ?>
    <a href="dashboard.php?estado=todos" class="menu-item <?php echo $activo_todos; ?>">
        <span class="material-icons">assignment</span> Ver todas
        <span class="menu-count"><?php echo $total_todas; ?></span>
    </a>
    <a href="dashboard.php?estado=en_progreso" class="menu-item menu-item-sub <?php echo $activo_progreso; ?>">
        <span class="material-icons">schedule</span> En progreso
        <span class="menu-count"><?php echo $total_progreso; ?></span>
    </a>
    <a href="dashboard.php?estado=finalizadas" class="menu-item menu-item-sub <?php echo $activo_finalizadas; ?>">
        <span class="material-icons">task_alt</span> Finalizadas
        <span class="menu-count"><?php echo $total_finalizadas; ?></span>
    </a>
    <a href="dashboard.php?estado=canceladas" class="menu-item menu-item-sub <?php echo $activo_canceladas; ?>">
        <span class="material-icons">cancel</span> Canceladas
        <span class="menu-count"><?php echo $total_canceladas; ?></span>
    </a>

    <div class="menu-label">PERSONAL</div>
    <div class="menu-item" onclick="abrirModal('modalListaPersonal')" style="cursor:pointer;">
        <span class="material-icons">group</span> Lista de Personal
    </div>

    <div class="menu-label">GESTIÓN DE USUARIOS</div>
    <div class="menu-item" onclick="abrirModal('modalListaUsuarios')" style="cursor:pointer;">
        <span class="material-icons">badge</span> Lista de Usuarios
    </div>

<<<<<<< HEAD
    <div class="menu-label">SISTEMA</div>
    <div class="menu-item" onclick="abrirBitacora()" style="cursor:pointer;" data-accion="bitacora">
        <span class="material-icons">history</span> Bitácora
    </div>

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid #f1f3f4;">
        <a href="auth/logout.php" class="menu-item" style="color: #d93025;">
            <span class="material-icons" style="color: #d93025;">logout</span> Cerrar Sesión
        </a>
    </div>
</aside>