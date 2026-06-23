(function () {
    function toggleMenu(event, id) {
        event.stopPropagation();
        var menu = document.getElementById('menu-' + id);
        if (!menu) return;
        var wasOpen = menu.classList.contains('show');
        window.closeAllDropdowns();
        if (wasOpen) return;
        menu.classList.add('show');

        var boton = document.querySelector('.btn-opciones[data-emp-id="' + id + '"]');
        if (boton) boton.setAttribute('aria-expanded', 'true');
    }

    function toggleMenuActividad(event, id) {
        event.stopPropagation();
        var menu = document.getElementById('menu-actividad-' + id);
        if (!menu) return;
        var wasOpen = menu.classList.contains('show');
        window.closeAllDropdowns();
        if (wasOpen) return;
        menu.classList.add('show');

        var boton = document.querySelector('.btn-opciones[data-act-id="' + id + '"]');
        if (boton) boton.setAttribute('aria-expanded', 'true');
    }

    function bindDelegatedActions() {
        document.addEventListener('click', function (event) {
            var triggerResponsables = event.target.closest('.btn-responsables-toggle');
            if (triggerResponsables) {
                event.stopPropagation();
                window.abrirResponsablesDesdeTrigger(triggerResponsables);
                return;
            }
            if (!event.target.closest('.responsables-popover')) {
                window.cerrarResponsablesAbiertos();
            }

            var btnDescripcion = event.target.closest('.btn-descripcion-expandir');
            if (btnDescripcion) {
                window.abrirInfoActividad('N/D', 'N/D', btnDescripcion.dataset.descripcion || 'Sin descripción');
                return;
            }

            var botonEditar = event.target.closest('.btn-accion-editar');
            if (botonEditar) {
                var idEditar = botonEditar.dataset.empId;
                if (!idEditar) return;
                window.closeAllDropdowns();
                window.prepararEdicion(idEditar);
                return;
            }

            var botonEliminar = event.target.closest('.btn-accion-eliminar');
            if (botonEliminar) {
                var idEliminar = botonEliminar.dataset.empId;
                var nombre = decodeURIComponent(botonEliminar.dataset.empNombre || '');
                if (!idEliminar) return;
                window.closeAllDropdowns();
                window.eliminarEmpleado(idEliminar, nombre);
                return;
            }

            var botonInfoEmpleado = event.target.closest('.btn-accion-info-empleado');
            if (botonInfoEmpleado) {
                window.closeAllDropdowns();
                window.abrirInfoPerfil({
                    nombre: decodeURIComponent(botonInfoEmpleado.dataset.nombre || 'N/D'),
                    apellido: decodeURIComponent(botonInfoEmpleado.dataset.apellido || 'N/D'),
                    formacion: decodeURIComponent(botonInfoEmpleado.dataset.formacion || 'N/D'),
                    username: 'N/D',
                    correo: decodeURIComponent(botonInfoEmpleado.dataset.correo || 'N/D'),
                    telefono: decodeURIComponent(botonInfoEmpleado.dataset.telefono || 'N/D'),
                    esEmpleado: true
                });
                return;
            }

            var botonOpciones = event.target.closest('.btn-opciones');
            if (botonOpciones) {
                var idEmp = botonOpciones.dataset.empId;
                var idAct = botonOpciones.dataset.actId;
                if (idEmp) {
                    toggleMenu(event, idEmp);
                    return;
                }
                if (idAct) {
                    toggleMenuActividad(event, idAct);
                    return;
                }
            }

            var botonActividadEditar = event.target.closest('.btn-accion-actividad-editar');
            if (botonActividadEditar) {
                var idActividadEditar = botonActividadEditar.dataset.actId;
                if (!idActividadEditar) return;
                window.closeAllDropdowns();
                window.prepararEdicionActividad(idActividadEditar);
                return;
            }

            var botonActividadEliminar = event.target.closest('.btn-accion-actividad-eliminar');
            if (botonActividadEliminar) {
                var idActividadEliminar = botonActividadEliminar.dataset.actId;
                if (!idActividadEliminar) return;
                window.closeAllDropdowns();
                window.eliminarActividad(idActividadEliminar);
                return;
            }

            var botonActividadInfo = event.target.closest('.btn-accion-actividad-info');
            if (botonActividadInfo) {
                window.closeAllDropdowns();
                window.abrirInfoActividad(
                    botonActividadInfo.dataset.fechaRegistro || 'N/D',
                    botonActividadInfo.dataset.usuarioRegistro || 'N/D',
                    botonActividadInfo.dataset.descripcion || '',
                    botonActividadInfo.dataset.actId || '',
                    botonActividadInfo.dataset.duracion || '—',
                    botonActividadInfo.dataset.fechaFin || ''
                );
            }

            var botonFinalizar = event.target.closest('.btn-accion-actividad-finalizar');
            if (botonFinalizar) {
                const actId = botonFinalizar.dataset.actId;
                if (!actId) return;
                window.closeAllDropdowns();
                fetch('actividades/finalizar_actividad.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'id=' + encodeURIComponent(actId)
                })
                    .then(res => res.text())
                    .then((txt) => {
                        if (String(txt).trim() === 'success') {
                            location.reload();
                            return;
                        }
                        alert('No se pudo finalizar la actividad: ' + txt);
                    })
                    .catch(() => alert('Error de red al finalizar actividad'));
            }
        });
    }

    function initDashboardModules() {
        if (typeof window.bindGlobalUiEvents === 'function') {
            window.bindGlobalUiEvents();
        }
        if (typeof window.initSecurityQuestions === 'function') {
            window.initSecurityQuestions(document);
        }
        bindDelegatedActions();
    }

    window.toggleMenu = toggleMenu;
    window.toggleMenuActividad = toggleMenuActividad;
    window.initDashboardModules = initDashboardModules;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initDashboardModules);
    } else {
        initDashboardModules();
    }
})();
