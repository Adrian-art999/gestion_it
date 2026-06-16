(function () {
    'use strict';

    // ── Utilidad: verificar permiso desde window.PERMISOS ──────────────
    function tienePermiso(permiso) {
        return window.PERMISOS && window.PERMISOS[permiso] === true;
    }

    // ── SweetAlert2 de acceso denegado ─────────────────────────────────
    function mostrarPermisoDenegado() {
        Swal.fire({
            icon: 'info',
            title: 'Acceso restringido',
            text: 'No tienes permiso para este apartado',
            confirmButtonText: 'Entendido',
            customClass: {
                popup: 'osti-swal',
                confirmButton: 'osti-btn'
            },
            buttonsStyling: false,
            showClass: {
                popup: 'swal2-show'
            }
        });
    }

    // ── Interceptor de clics para botones prohibidos ──────────────────
    document.addEventListener('click', function (e) {
        var target = e.target;

        // Botón Eliminar Actividad
        if (target.closest('.btn-accion-actividad-eliminar')) {
            if (!tienePermiso('actividades_eliminar')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Botón Eliminar Empleado (no oculto, interceptor puro)
        if (target.closest('.btn-accion-eliminar') && !target.closest('[data-act-id]')) {
            if (!tienePermiso('empleados_eliminar')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Botón Editar Empleado
        if (target.closest('.btn-accion-editar')) {
            if (!tienePermiso('empleados_editar')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Botón Info Empleado
        if (target.closest('.btn-accion-info-empleado')) {
            if (!tienePermiso('empleados_info')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Botón "Lista de Usuarios" en sidebar
        if (target.closest('[onclick*="modalListaUsuarios"]')) {
            if (!tienePermiso('usuarios_listar')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Botón Finalizar Actividad
        if (target.closest('.btn-accion-actividad-finalizar')) {
            if (!tienePermiso('actividades_finalizar')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Bitácora
        if (target.closest('[onclick*="bitacora"]') || target.closest('[data-accion="bitacora"]')) {
            if (!tienePermiso('bitacora')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Nuevo empleado
        if (target.closest('[data-accion="nuevo-empleado"]')) {
            if (!tienePermiso('empleados_registrar')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Nuevo usuario
        if (target.closest('[data-accion="nuevo-usuario"]')) {
            if (!tienePermiso('usuarios_registrar')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }

        // Reporte PDF
        if (target.closest('[data-accion="reporte-pdf"]')) {
            if (!tienePermiso('reportes_pdf')) {
                e.preventDefault();
                e.stopPropagation();
                mostrarPermisoDenegado();
                return;
            }
        }
    }, true); // useCapture para interceptar antes de otras acciones

    // ── Modal de gestión de Roles ──────────────────────────────────────
    function abrirModalRoles(usuarioId) {
        if (!tienePermiso('roles_gestionar')) {
            mostrarPermisoDenegado();
            return;
        }

        fetch('auth/obtener_permisos.php?id=' + encodeURIComponent(usuarioId))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (!data.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'No se pudieron cargar los permisos',
                        confirmButtonText: 'Cerrar',
                        customClass: { popup: 'osti-swal', confirmButton: 'osti-btn' },
                        buttonsStyling: false
                    });
                    return;
                }

                var user = data.usuario;
                var permisos = data.permisos || {};
                var esSuper = data.es_superadmin;
                var permisosList = Object.keys(permisos);

                var html = '<div style="font-size:14px;color:#3c4043;text-align:left;">';
                html += '<p style="margin-bottom:14px;color:#5f6368;">'
                    + '<strong>' + escapeHtml(user.nombre) + '</strong>'
                    + ' (' + escapeHtml(user.username) + ')'
                    + ' — ' + '<span style="background:#e8f0fe;padding:2px 10px;border-radius:12px;font-size:12px;">' + escapeHtml(user.rol) + '</span>'
                    + '</p>';

                if (esSuper) {
                    html += '<div style="background:#fce8e6;color:#b3261e;padding:12px;border-radius:8px;font-size:13px;margin-bottom:14px;">'
                        + 'Este usuario es Superadmin y tiene todos los permisos por defecto.</div>';
                }

                html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">';

                var labels = {
                    actividades_editar: 'Editar Actividades',
                    actividades_eliminar: 'Eliminar Actividades',
                    actividades_finalizar: 'Finalizar Actividades',
                    actividades_info: 'Información Actividades',
                    empleados_listar: 'Listar Empleados',
                    empleados_registrar: 'Registrar Empleados',
                    empleados_editar: 'Editar Empleados',
                    empleados_eliminar: 'Eliminar Empleados',
                    empleados_info: 'Información Empleados',
                    usuarios_listar: 'Listar Usuarios',
                    usuarios_registrar: 'Registrar Usuarios',
                    usuarios_editar: 'Editar Usuarios',
                    usuarios_eliminar: 'Eliminar Usuarios',
                    reportes_pdf: 'Reportes PDF',
                    bitacora: 'Bitácora',
                    roles_gestionar: 'Gestionar Roles'
                };

                permisosList.forEach(function (key) {
                    var label = labels[key] || key;
                    var checked = permisos[key] === true;
                    html += '<label style="display:flex;align-items:center;gap:8px;padding:6px 0;font-size:13px;cursor:pointer;">'
                        + '<input type="checkbox" name="' + key + '" value="1" ' + (checked ? 'checked' : '') + ' '
                        + (esSuper ? 'disabled' : '')
                        + ' style="width:16px;height:16px;accent-color:#1a73e8;cursor:pointer;">'
                        + '<span>' + escapeHtml(label) + '</span>'
                        + '</label>';
                });

                html += '</div></div>';

                Swal.fire({
                    title: 'Permisos de usuario',
                    html: html,
                    showCancelButton: !esSuper,
                    confirmButtonText: 'Guardar cambios',
                    cancelButtonText: 'Cancelar',
                    showCloseButton: true,
                    customClass: {
                        popup: 'osti-swal',
                        confirmButton: 'osti-btn',
                        cancelButton: 'osti-btn-cancel'
                    },
                    buttonsStyling: false,
                    width: 520,
                    preConfirm: function () {
                        if (esSuper) return false;
                        var formData = new FormData();
                        formData.append('id', user.id);
                        permisosList.forEach(function (key) {
                            var cb = Swal.getPopup().querySelector('input[name="' + key + '"]');
                            formData.append(key, cb && cb.checked ? '1' : '0');
                        });
                        return fetch('auth/guardar_permisos.php', { method: 'POST', body: formData })
                            .then(function (r) { return r.json(); })
                            .then(function (d) {
                                if (!d.ok) {
                                    Swal.showValidationMessage(d.message || 'Error al guardar');
                                }
                                return d;
                            });
                    }
                }).then(function (result) {
                    if (result.isConfirmed && result.value && result.value.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Permisos actualizados',
                            text: 'Los permisos se guardaron correctamente.',
                            confirmButtonText: 'Aceptar',
                            customClass: { popup: 'osti-swal', confirmButton: 'osti-btn' },
                            buttonsStyling: false
                        });
                    }
                });
            })
            .catch(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo cargar la información del usuario.',
                    confirmButtonText: 'Cerrar',
                    customClass: { popup: 'osti-swal', confirmButton: 'osti-btn' },
                    buttonsStyling: false
                });
            });
    }

    function escapeHtml(str) {
        if (typeof str !== 'string') return String(str || '');
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // ── Bitácora ────────────────────────────────────────────────
    function abrirBitacora() {
        if (!tienePermiso('bitacora')) {
            mostrarPermisoDenegado();
            return;
        }
        window.location.href = 'bitacora.php';
    }

    // Exponer globalmente
    window.abrirModalRoles = abrirModalRoles;
    window.abrirBitacora = abrirBitacora;
    window.tienePermiso = tienePermiso;
    window.mostrarPermisoDenegado = mostrarPermisoDenegado;

})();
