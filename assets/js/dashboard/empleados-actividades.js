(function () {
    function cargarEmpleados(busqueda) {
        var term = typeof busqueda === 'string' ? busqueda : '';
        fetch('empleados/listar_empleados.php?buscar=' + encodeURIComponent(term))
            .then(function (res) { return res.json(); })
            .then(function (data) {
                var body = document.getElementById('listaEmpleadosBody');
                if (!body) return;
                body.innerHTML = '';

                if (!Array.isArray(data) || data.length === 0) {
                    body.innerHTML = '<tr><td colspan="4" class="mensaje-vacio">No hay resultados</td></tr>';
                    return;
                }

                data.forEach(function (emp) {
                    var nombre = String(emp.nombre || '');
                    var apellido = String(emp.apellido || 'N/D');
                    var etiquetaVisual = nombre; // Sin prefijo [#ID] visible
                    var nombreCodificado = encodeURIComponent(nombre);
                    var apellidoCodificado = encodeURIComponent(apellido);
                    var formacionCodificada = encodeURIComponent(String(emp.formacion || 'N/D'));
                    var correoCodificado = encodeURIComponent(String(emp.correo || 'N/D'));
                    var telefonoCodificado = encodeURIComponent(String(emp.telefono || 'N/D'));

                    body.innerHTML += ''
                        + '<tr>'
                        + '<td>' + etiquetaVisual + '</td>'
                        + '<td>' + apellido + '</td>'
                        + '<td>' + (emp.formacion || 'N/D') + '</td>'
                        + '<td style="text-align: right; overflow: visible;">'
                        + '  <div class="opciones-menu">'
                        + '      <span class="material-icons btn-opciones" data-emp-id="' + emp.id + '" role="button" tabindex="0" aria-label="Abrir opciones" aria-expanded="false">more_vert</span>'
                        + '      <div id="menu-' + emp.id + '" class="dropdown-opciones">'
                        + '          <button type="button" class="btn-accion-editar" data-emp-id="' + emp.id + '"><span class="material-icons">edit</span> Editar</button>'
                        + (window.ES_ADMIN ? '          <button type="button" class="btn-accion-eliminar" data-emp-id="' + emp.id + '" data-emp-nombre="' + nombreCodificado + '" style="color:#d93025"><span class="material-icons" style="color:#d93025">delete</span> Eliminar</button>' : '')
                        + '          <button type="button" class="btn-accion-info-empleado" data-nombre="' + nombreCodificado + '" data-apellido="' + apellidoCodificado + '" data-formacion="' + formacionCodificada + '" data-correo="' + correoCodificado + '" data-telefono="' + telefonoCodificado + '"><span class="material-icons">info</span> Info</button>'
                        + '      </div>'
                        + '  </div>'
                        + '</td>'
                        + '</tr>';
                });
            });
    }

    function prepararEdicion(id) {
        fetch('empleados/obtener_empleado.php?id=' + encodeURIComponent(id))
            .then(function (res) { return res.json(); })
            .then(function (emp) {
                window.cerrarModal('modalListaPersonal');
                window.abrirModal('modalEmpleado');

                var titulo = document.getElementById('tituloEmpleado');
                var btn = document.getElementById('btnGuardarEmpleado');
                if (titulo) titulo.innerText = 'Editar Personal';
                if (btn) btn.innerText = 'Actualizar Personal';

                var form = document.getElementById('formNuevoEmpleado');
                if (!form) return;

                form.nombre.value = emp.nombre || '';
                form.apellido.value = emp.apellido || '';
                form.formacion.value = emp.formacion || '';

                if (emp.correo && emp.correo !== 'N/D') {
                    var selGmail = form.querySelector('select[onchange*="div_gmail"]');
                    if (selGmail) {
                        selGmail.value = 'si';
                        window.toggleInput(selGmail, 'div_gmail');
                        form.correo.value = emp.correo;
                    }
                }

                if (emp.telefono && emp.telefono !== 'N/D') {
                    var selTel = form.querySelector('select[onchange*="div_tel"]');
                    if (selTel) {
                        selTel.value = 'si';
                        window.toggleInput(selTel, 'div_tel');
                        form.telefono.value = emp.telefono;
                    }
                }

                var inputId = form.querySelector('input[name="id_empleado"]');
                if (!inputId) {
                    inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'id_empleado';
                    form.appendChild(inputId);
                }
                inputId.value = id;
            });
    }

    function guardarEmpleado(e) {
        e.preventDefault();
        fetch('empleados/procesar_empleado.php', { method: 'POST', body: new FormData(e.target) })
            .then(function (res) { return res.text(); })
            .then(function (data) {
                if (String(data).trim() === 'success') {
                    window.cerrarModal('modalEmpleado');
                    location.reload();
                    return;
                }
                alert(data);
            });
    }

    function eliminarEmpleado(id, nombre) {
        if (!confirm('¿Seguro que desea eliminar a ' + nombre + '?')) return;
        fetch('empleados/eliminar_empleado.php?id=' + encodeURIComponent(id))
            .then(function (res) { return res.text(); })
            .then(function (data) {
                if (String(data).trim() === 'success') {
                    var inputBusqueda = document.getElementById('inputBusqueda');
                    cargarEmpleados(inputBusqueda ? inputBusqueda.value : '');
                    return;
                }
                alert(data);
            });
    }

    function agregarSelect() {
        var contenedor = document.getElementById('contenedor-selects');
        if (!contenedor) return;

        // Validación: la última fila debe tener un empleado seleccionado
        var filas = contenedor.querySelectorAll('.responsable-row');
        if (filas.length > 0) {
            var ultimaFila = filas[filas.length - 1];
            var ultimoHidden = ultimaFila.querySelector('.emp-hidden-id');
            if (!ultimoHidden || ultimoHidden.value.trim() === '') {
                alert('Debe seleccionar un empleado válido antes de añadir otro.');
                ultimaFila.querySelector('.emp-search-input').focus();
                return;
            }
        }

        // Límite de 5 responsables
        if (filas.length >= 5) {
            alert('Máximo 5 responsables por actividad.');
            return;
        }

        // Usar la función global del footer para crear la nueva fila
        if (typeof crearFilaAutocomplete === 'function') {
            contenedor.appendChild(crearFilaAutocomplete('', '', true));
        }
    }

    // Estas funciones ya no operan sobre <select> nativos; se mantienen
    // como stubs para no romper ninguna llamada residual.
    function aplicarFiltroDuplicados() { /* gestionado por el motor autocomplete */ }
    function actualizarTodosLosSelectores() { /* gestionado por el motor autocomplete */ }

    function removerResponsable(btn) {
        // Este handler queda como respaldo; el nuevo botón usa addEventListener
        if (btn && btn.parentElement) btn.parentElement.remove();
    }

    // Inicializar event listeners cuando se carga el DOM
    document.addEventListener('DOMContentLoaded', function() {
        var contenedor = document.getElementById('contenedor-selects');
        if (contenedor) {
            // El motor de autocomplete gestiona sus propios eventos internamente
        }

        // Event listener para botones de info de empleados usando delegación de eventos
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-accion-info-empleado');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();
                
                var nombre = btn.getAttribute('data-nombre');
                var apellido = btn.getAttribute('data-apellido');
                var formacion = btn.getAttribute('data-formacion');
                var correo = btn.getAttribute('data-correo');
                var telefono = btn.getAttribute('data-telefono');
                
                if (typeof window.abrirInfoEmpleado === 'function') {
                    window.abrirInfoEmpleado(nombre, apellido, formacion, correo, telefono);
                } else {
                    console.error('La función abrirInfoEmpleado no está disponible');
                }
            }
        });
    });

    function guardarActividad(e) {
        e.preventDefault();
        fetch('actividades/procesar_actividad.php', { method: 'POST', body: new FormData(e.target) })
            .then(function (res) { return res.text(); })
            .then(function (data) {
                if (String(data).trim() === 'success') {
                    location.reload();
                    return;
                }
                alert('Error: ' + data);
            });
    }

    function setModoActividad(esEdicion) {
        var titulo = document.getElementById('tituloActividad');
        var boton = document.getElementById('btnGuardarActividad');
        var inputId = document.getElementById('idActividadEditar');
        if (titulo) titulo.innerText = esEdicion ? 'Editar Actividad' : 'Registrar Nueva Actividad';
        if (boton) boton.innerText = esEdicion ? 'Actualizar Actividad' : 'Guardar Actividad';
        if (!esEdicion && inputId) inputId.value = '';

        // Al abrir para NUEVA actividad, reiniciar el contenedor de responsables
        // con una sola fila de autocomplete vacía y limpia.
        if (!esEdicion && typeof crearFilaAutocomplete === 'function') {
            var contenedor = document.getElementById('contenedor-selects');
            if (contenedor) {
                contenedor.innerHTML = '';
                contenedor.appendChild(crearFilaAutocomplete('', '', false));
            }
        }
    }

    function prepararEdicionActividad(id) {
        fetch('actividades/obtener_actividad.php?id=' + encodeURIComponent(id))
            .then(function (res) { return res.json(); })
            .then(function (act) {
                window.abrirModal('modalActividad');
                setModoActividad(true);

                var form = document.getElementById('formNuevaActividad');
                if (!form || !act) return;

                document.getElementById('idActividadEditar').value = id;
                form.descripcion.value = act.descripcion || '';
                form.fecha.value = act.fecha_inicio ? String(act.fecha_inicio).slice(0, 10) : '';
                form.estado.value = act.estado || 'En progreso';
                form.area.value = act.area || 'Informática';

                var areaInput = document.getElementById('m_area');
                if (form.area.value !== 'Informática' && form.area.value !== 'Presupuesto') {
                    form.area.value = 'OTRA';
                    if (areaInput) {
                        areaInput.style.display = 'block';
                        areaInput.value = act.area || '';
                    }
                } else if (areaInput) {
                    areaInput.style.display = 'none';
                    areaInput.value = '';
                }

                // --- Reconstruir filas de responsables con el motor autocomplete ---
                var contenedor = document.getElementById('contenedor-selects');
                if (!contenedor) return;
                contenedor.innerHTML = ''; // Limpiar filas anteriores

                var responsables = [];
                try {
                    responsables = JSON.parse(act.responsables_data || '[]');
                } catch (err) {
                    responsables = [];
                }
                if (!Array.isArray(responsables) || responsables.length === 0) {
                    responsables = [{ id: '', nombre: '' }];
                }

                // Obtener catálogo para buscar la etiqueta por ID
                var catalogoFn = (typeof obtenerCatalogoEmpleados === 'function') ? obtenerCatalogoEmpleados : function() { return []; };
                var catalogo = catalogoFn();

                responsables.forEach(function (r, index) {
                    var empId = r.id ? String(r.id) : '';
                    var empLabel = '';
                    if (empId) {
                        var entrada = catalogo.find(function(c) { return c.id === empId; });
                        empLabel = entrada ? entrada.label : (r.nombre || '');
                    }
                    if (typeof crearFilaAutocomplete === 'function') {
                        contenedor.appendChild(crearFilaAutocomplete(empId, empLabel, index > 0));
                    }
                });
            })
            .catch(function () {
                alert('No se pudo cargar la actividad para edición.');
            });
    }

    function eliminarActividad(id) {
        if (!confirm('¿Seguro que deseas eliminar esta actividad?')) return;
        fetch('actividades/eliminar_actividad.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
            .then(function (res) { return res.text(); })
            .then(function (data) {
                if (String(data).trim() === 'success') {
                    location.reload();
                    return;
                }
                alert('No se pudo eliminar la actividad.');
            });
    }

    function abrirInfoActividad(fecha, usuario, descripcion, actividadId) {
        var infoFecha = document.getElementById('infoActividadFecha');
        var infoUsuario = document.getElementById('infoActividadUsuario');
        var infoDesc = document.getElementById('infoActividadDescripcion');
        var boxHistorial = document.getElementById('infoActividadHistorial');

        if (infoFecha) infoFecha.innerText = fecha;
        if (infoUsuario) infoUsuario.innerText = usuario;
        if (infoDesc) infoDesc.innerText = descripcion || 'Sin descripción';
        if (boxHistorial) boxHistorial.innerHTML = 'Cargando historial...';

        if (actividadId) {
            fetch('actividades/historial_actividad.php?id=' + encodeURIComponent(actividadId))
                .then(function (res) { return res.json(); })
                .then(function (items) {
                    if (!boxHistorial) return;
                    if (!Array.isArray(items) || items.length === 0) {
                        boxHistorial.innerHTML = '<div style="color:#70757a;">Sin registros de historial.</div>';
                        return;
                    }
                    boxHistorial.innerHTML = items.map(function (item) {
                        var fechaItem = item.creado_en || '';
                        var detalle = item.detalle || '';
                        var accion = item.accion || '';
                        return '<div style="padding:8px 0; border-bottom:1px solid #f1f3f4;">'
                            + '<div style="font-weight:600; color:#3c4043;">' + accion + '</div>'
                            + '<div style="font-size:13px; color:#5f6368;">' + detalle + '</div>'
                            + '<div style="font-size:12px; color:#9aa0a6; margin-top:4px;">' + fechaItem + '</div>'
                            + '</div>';
                    }).join('');
                })
                .catch(function () {
                    if (boxHistorial) boxHistorial.innerHTML = '<div style="color:#d93025;">No se pudo cargar el historial.</div>';
                });
        } else if (boxHistorial) {
            boxHistorial.innerHTML = '<div style="color:#70757a;">Sin registros de historial.</div>';
        }

        window.abrirModal('modalInfoActividad');
    }

    function abrirInfoEmpleado(nombre, apellido, formacion, correo, telefono) {
        // Crear modal de info de empleado dinámicamente
        const modalInfoEmpleado = document.createElement('div');
        modalInfoEmpleado.id = 'modalInfoEmpleado';
        modalInfoEmpleado.className = 'modal';
        modalInfoEmpleado.style.display = 'block';
        modalInfoEmpleado.style.zIndex = '2000';
        modalInfoEmpleado.style.backdropFilter = 'blur(4px)';
        modalInfoEmpleado.style.backgroundColor = 'rgba(0, 0, 0, 0.4)';
        modalInfoEmpleado.innerHTML = `
            <div class="modal-content" style="max-width: 500px; border:1px solid #dadce0; box-shadow:0 12px 28px rgba(60,64,67,0.12);">
                <span class="material-icons btn-close-modal" onclick="this.closest('.modal').remove()">close</span>
                <h2 style="display:flex; align-items:center; gap:10px; margin-bottom:20px; color:#3c4043;">
                    <span class="material-icons">info</span> Información de Empleado
                </h2>
                <div style="display:grid; gap:12px;">
                    <div><strong>Nombre:</strong> ${decodeURIComponent(nombre)}</div>
                    <div><strong>Apellido:</strong> ${decodeURIComponent(apellido)}</div>
                    <div><strong>Formación:</strong> ${decodeURIComponent(formacion)}</div>
                    <div><strong>Correo:</strong> ${decodeURIComponent(correo)}</div>
                    <div><strong>Teléfono:</strong> ${decodeURIComponent(telefono)}</div>
                </div>
            </div>
        `;
        document.body.appendChild(modalInfoEmpleado);
        
        // Cerrar al hacer clic fuera del modal
        modalInfoEmpleado.addEventListener('click', function(e) {
            if (e.target === modalInfoEmpleado) {
                modalInfoEmpleado.remove();
            }
        });
    }

    function guardarUsuarioAdmin(e) {
        e.preventDefault();
        
        var formData = new FormData(e.target);
        
        // Validación frontend del username
        var username = formData.get('username');
        if (!username || username.trim() === '') {
            alert('El username es obligatorio y no puede estar vacío.');
            return;
        }

        // Depuración: verificar qué datos se están enviando
        console.log('Datos del formulario:');
        for (var pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        fetch('auth/crear_usuario_admin.php', { method: 'POST', body: formData })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                console.log('Respuesta del servidor:', data);
                if (data.ok) {
                    alert(data.message);
                    window.cerrarModal('modalAddUsuario');
                    location.reload();
                    return;
                }
                alert(data.message || 'No se pudo registrar el usuario.');
            })
            .catch(function () {
                alert('Error de red al registrar usuario.');
            });
    }

    function filtrarTabla() {
        var input = document.getElementById('buscador');
        var filtroEstado = document.getElementById('filtroEstado');
        var tabla = document.getElementById('miTabla');
        if (!input || !filtroEstado || !tabla) return;

        var texto = input.value.trim().toLowerCase();
        var estado = filtroEstado.value;
        var filas = tabla.querySelectorAll('tbody tr[data-row-actividad]');
        var visibles = 0;

        filas.forEach(function (fila) {
            var contenido = fila.innerText.toLowerCase();
            var estadoFila = fila.dataset.estado || '';
            var coincideTexto = contenido.indexOf(texto) !== -1;
            var coincideEstado = (estado === 'todos') || (estadoFila === estado);
            fila.style.display = (coincideTexto && coincideEstado) ? '' : 'none';
            if (coincideTexto && coincideEstado) visibles++;
        });

        var filaSin = document.getElementById('sinResultadosActividades');
        if (filaSin) {
            filaSin.style.display = visibles === 0 ? '' : 'none';
        }
    }

    function cerrarResponsablesAbiertos() {
        document.querySelectorAll('.responsables-popover.abierto').forEach(function (el) {
            el.classList.remove('abierto');
            el.innerHTML = '';
        });
    }

    function abrirResponsablesDesdeTrigger(trigger) {
        var row = trigger.closest('td');
        var box = row ? row.querySelector('.responsables-popover') : null;
        if (!box) return;

        var wasOpen = box.classList.contains('abierto');
        cerrarResponsablesAbiertos();
        if (wasOpen) return;

        var items = [];
        try {
            items = JSON.parse(trigger.dataset.responsables || '[]');
        } catch (e) {
            items = [];
        }

        box.innerHTML = items.map(function (item) {
            return '<div style="padding:4px 0; color:#3c4043;">' + item + '</div>';
        }).join('');
        box.classList.add('abierto');
    }

    window.cargarEmpleados = cargarEmpleados;
    window.prepararEdicion = prepararEdicion;
    window.guardarEmpleado = guardarEmpleado;
    window.eliminarEmpleado = eliminarEmpleado;
    window.agregarSelect = agregarSelect;
    window.removerResponsable = removerResponsable;
    window.guardarActividad = guardarActividad;
    window.setModoActividad = setModoActividad;
    window.prepararEdicionActividad = prepararEdicionActividad;
    window.eliminarActividad = eliminarActividad;
    window.abrirInfoActividad = abrirInfoActividad;
    window.abrirInfoEmpleado = abrirInfoEmpleado;
    window.guardarUsuarioAdmin = guardarUsuarioAdmin;
    window.filtrarTabla = filtrarTabla;
    window.cerrarResponsablesAbiertos = cerrarResponsablesAbiertos;
    window.abrirResponsablesDesdeTrigger = abrirResponsablesDesdeTrigger;
    
    // Sobrescribir abrirModal para cargar usuarios cuando se abra modalListaUsuarios
    const originalAbrirModal = window.abrirModal;
    window.abrirModal = function(id) {
        if (typeof originalAbrirModal === 'function') {
            originalAbrirModal(id);
        } else {
            const modal = document.getElementById(id);
            if (modal) modal.style.display = 'block';
        }
        
        if (id === 'modalListaUsuarios' && typeof cargarUsuarios === 'function') {
            cargarUsuarios();
        }
    };
    
    // Exponer guardarEdicionUsuario si existe en el scope global
    if (typeof guardarEdicionUsuario === 'function') {
        window.guardarEdicionUsuario = guardarEdicionUsuario;
    }
})();
