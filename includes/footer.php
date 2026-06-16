<div id="modalActividad" class="modal">
    <div class="modal-content">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalActividad')">close</span>
        <div style="color: #1a73e8; font-size: 20px; font-weight: 500; display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
            <span class="material-icons">add_task</span> <span id="tituloActividad">Registrar Nueva Actividad</span>
        </div>
        <form id="formNuevaActividad" onsubmit="guardarActividad(event)">
            <input type="hidden" name="id_actividad" id="idActividadEditar">
            <label style="margin-top:0;">Asignar Responsable(s)</label>
            <!-- Catálogo oculto de empleados (fuente de datos, no se muestra al usuario) -->
            <select id="emp-catalogo-oculto" style="display:none;" aria-hidden="true">
                <option value=""></option>
                <?php 
                $res_emps = mysqli_query($conn, "SELECT id, nombre, apellido FROM empleados ORDER BY nombre ASC");
                while($e = mysqli_fetch_assoc($res_emps)) {
                    $empId = (int) ($e['id'] ?? 0);
                    $empNombre = trim((string) (($e['nombre'] ?? '') . ' ' . ($e['apellido'] ?? '')));
                    // El value conserva el ID para la lógica PHP; la etiqueta muestra solo el nombre
                    echo "<option value='{$empId}'>" . htmlspecialchars($empNombre, ENT_QUOTES, 'UTF-8') . "</option>";
                }
                ?>
            </select>
            <div id="contenedor-selects"></div>
            <div id="btn-agregar-responsable" onclick="agregarSelect()" style="color:#1a73e8; font-size:13px; cursor:pointer; margin-bottom: 20px; display:flex; align-items:center; gap:5px;">
                <span class="material-icons" style="font-size:18px;">add_circle_outline</span> Añadir otro responsable
            </div>
            <div>
                <label>Área</label>
                <select name="area" onchange="document.getElementById('m_area').style.display=(this.value=='OTRA'?'block':'none')">
                    <option value="Informática">Informática</option>
                    <option value="Presupuesto">Presupuesto</option>
                    <option value="OTRA">Otra (Especificar)</option>
                </select>
                <input type="text" name="area_manual" id="m_area" placeholder="¿Cuál área?" maxlength="50" style="display:none; margin-top:5px;">
            </div>
            <label>Descripción</label>
            <textarea name="descripcion" rows="2" placeholder="Detalles de la tarea..." maxlength="150" required></textarea>
            <div class="form-grid">
                <div>
                    <label>Estado</label>
                    <select name="estado" id="estadoActividad">
                        <option value="En progreso">En progreso</option>
                        <option value="Finalizada">Finalizada</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
                <div>
                    <label>Fecha de Inicio</label>
                    <input type="date" name="fecha" value="<?php echo date('Y-m-d'); ?>">
                </div>
            </div>
            <div style="margin-top: 35px; display: flex; justify-content: flex-end; gap: 12px; padding-bottom:10px;">
                <button type="button" class="btn-cancel" onclick="cerrarModal('modalActividad')">Cancelar</button>
                <button type="submit" class="btn-save" id="btnGuardarActividad">Guardar Actividad</button>
            </div>
        </form>
    </div>
</div>

<div id="modalInfoActividad" class="modal">
    <div class="modal-content" style="max-width: 560px;">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalInfoActividad')">close</span>
        <h2 style="display:flex; align-items:center; gap:10px; margin-bottom:20px; color:#3c4043;">
            <span class="material-icons">info</span> Información de Actividad
        </h2>
        <div style="display:grid; gap:12px;">
            <div><strong>Fecha de registro:</strong> <span id="infoActividadFecha">N/D</span></div>
            <div><strong>Usuario que registró:</strong> <span id="infoActividadUsuario">N/D</span></div>
<<<<<<< HEAD
            <div id="infoActividadDuracionRow" style="display:none;">
                <strong>Tiempo de duración:</strong> <span id="infoActividadDuracion">—</span>
            </div>
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
            <div>
                <strong>Descripción completa:</strong>
                <div id="infoActividadDescripcion" style="margin-top:8px; background:#f8f9fa; border:1px solid #dadce0; border-radius:8px; padding:10px; white-space: pre-wrap;"></div>
            </div>
            <div>
                <strong>Historial de cambios:</strong>
                <div id="infoActividadHistorial" style="margin-top:8px; background:#fff; border:1px solid #dadce0; border-radius:8px; padding:10px; max-height:180px; overflow-y:auto;"></div>
            </div>
        </div>
    </div>
</div>

<div id="modalAddUsuario" class="modal">
    <div class="modal-content">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalAddUsuario')">close</span>
        <h2 style="display:flex; align-items:center; gap:10px; margin-bottom:16px; color:#3c4043;">
            <span class="material-icons">person_add</span> Registrar Usuario
        </h2>
        <form id="formNuevoUsuario" onsubmit="guardarUsuarioAdmin(event)">
            <div class="form-grid">
                <div>
                    <label>Nombre</label>
                    <input type="text" name="nombre" maxlength="30" required>
                </div>
                <div>
                    <label>Apellido</label>
                    <input type="text" name="apellido" maxlength="30" required>
                </div>
            </div>
            <label>Username</label>
            <input type="text" name="username" maxlength="20" required>
            <label>Cargo o Formación</label>
            <input type="text" name="formacion" placeholder="Ej: Coordinador IT" maxlength="50">

            <div class="form-grid">
                <div>
                    <label>¿Tiene Gmail?</label>
                    <select onchange="toggleInput(this, 'div_usuario_gmail')">
                        <option value="no">No</option>
                        <option value="si">Sí</option>
                    </select>
                    <div id="div_usuario_gmail" style="display:none; margin-top:6px;">
                        <input type="email" name="correo" placeholder="usuario@gmail.com" maxlength="50">
                    </div>
                </div>
                <div>
                    <label>¿Tiene Teléfono?</label>
                    <select onchange="toggleInput(this, 'div_usuario_tel')">
                        <option value="no">No</option>
                        <option value="si">Sí</option>
                    </select>
                    <div id="div_usuario_tel" style="display:none; margin-top:6px;">
                        <input type="tel" name="telefono" placeholder="0412-0000000" maxlength="15" inputmode="tel" pattern="[0-9+\-]+">
                    </div>
                </div>
            </div>

            <label>Contraseña</label>
            <input type="password" name="password" required>
            <small style="display:block; margin-top:6px; color:#5f6368;">Mínimo 8 caracteres y al menos un número.</small>

            <div style="margin-top:16px;">
                <label style="margin-top:0;">Preguntas de seguridad (3 de 3)</label>
                <?php
                $preguntas_recuperacion = [
                    'mascota' => 'Nombre de tu mascota',
                    'pelicula' => 'Película favorita',
                    'comida' => 'Comida favorita',
                    'ciudad_nacimiento' => 'Ciudad de nacimiento',
                    'primer_colegio' => 'Primer colegio',
                    'cancion_favorita' => 'Canción favorita',
                ];
                for ($i = 1; $i <= 3; $i++):
                ?>
                    <div class="form-grid" style="grid-template-columns: 1.1fr 1fr; gap:10px; margin-bottom:10px;">
                        <select name="pregunta_<?php echo $i; ?>" required>
                            <option value="">Pregunta <?php echo $i; ?></option>
                            <?php foreach ($preguntas_recuperacion as $k => $label): ?>
                                <option value="<?php echo htmlspecialchars($k, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="text" name="respuesta_<?php echo $i; ?>" placeholder="Respuesta <?php echo $i; ?>" maxlength="50" required>
                    </div>
                <?php endfor; ?>
            </div>

            <div style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn-cancel" onclick="cerrarModal('modalAddUsuario')">Cancelar</button>
                <button type="submit" class="btn-save">Crear Usuario</button>
            </div>
        </form>
    </div>
</div>

<div id="modalReportePDF" class="modal">
    <div class="modal-content" style="max-width:560px;">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalReportePDF')">close</span>
        <h2 style="display:flex; align-items:center; gap:10px; margin-bottom:16px; color:#3c4043;">
            <span class="material-icons">picture_as_pdf</span> Generar Reporte PDF
        </h2>
<<<<<<< HEAD
        <form method="GET" action="reportes/reporte_actividades.php" target="_blank" id="formReportePDF">
=======
        <form method="GET" action="reportes/reporte_actividades.php" target="_blank">
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
            <div class="form-grid">
                <div>
                    <label>Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" required>
                </div>
                <div>
                    <label>Fecha Fin</label>
                    <input type="date" name="fecha_fin" required>
                </div>
            </div>
            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn-cancel" onclick="cerrarModal('modalReportePDF')">Cancelar</button>
                <button type="submit" class="btn-save">Generar PDF</button>
            </div>
        </form>
<<<<<<< HEAD
        <script>
        document.getElementById('formReportePDF').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            var data = new URLSearchParams(new FormData(form));
            data.set('validar', '1');
            fetch('reportes/reporte_actividades.php?' + data.toString())
                .then(function(r) { return r.json(); })
                .then(function(resp) {
                    if (resp.ok) {
                        data.delete('validar');
                        window.open('reportes/reporte_actividades.php?' + data.toString(), '_blank');
                        cerrarModal('modalReportePDF');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: resp.message || 'Error al generar el reporte',
                            confirmButtonText: 'Aceptar',
                            customClass: { popup: 'osti-swal', confirmButton: 'osti-btn' },
                            buttonsStyling: false
                        });
                    }
                })
                .catch(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo validar el reporte. Intente nuevamente.',
                        confirmButtonText: 'Aceptar',
                        customClass: { popup: 'osti-swal', confirmButton: 'osti-btn' },
                        buttonsStyling: false
                    });
                });
        });
        </script>
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    </div>
</div>

<div id="modalEmpleado" class="modal">
    <div class="modal-content">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalEmpleado')">close</span>
        <div style="color: #1a73e8; font-size: 20px; font-weight: 500; display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
            <span class="material-icons">person_add</span> <span id="tituloEmpleado">Registrar Personal</span>
        </div>
        <form id="formNuevoEmpleado" onsubmit="guardarEmpleado(event)">
            <div class="form-grid">
                <div>
                    <label>Nombre</label>
                    <input type="text" name="nombre" placeholder="Nombre" maxlength="30" required>
                </div>
                <div>
                    <label>Apellido</label>
                    <input type="text" name="apellido" placeholder="Apellido" maxlength="30" required>
                </div>
            </div>
            <label>Formación / Cargo</label>
            <input type="text" name="formacion" placeholder="Ej: Técnico en Soporte" maxlength="50" required style="width: 100%; margin-bottom: 15px;">
            <div class="form-grid">
                <div>
                    <label>¿Tiene Correo?</label>
                    <select onchange="toggleInput(this, 'div_gmail')">
                        <option value="no">No</option>
                        <option value="si">Sí</option>
                    </select>
                    <div id="div_gmail" style="display:none; margin-top:5px;">
                        <input type="email" name="correo" placeholder="ejemplo@gmail.com" maxlength="50">
                    </div>
                </div>
                <div>
                    <label>¿Teléfono?</label>
                    <select onchange="toggleInput(this, 'div_tel')">
                        <option value="no">No</option>
                        <option value="si">Sí</option>
                    </select>
                    <div id="div_tel" style="display:none; margin-top:5px;">
                        <input type="tel" name="telefono" placeholder="0412-0000000" maxlength="15" inputmode="tel" pattern="[0-9+\-]+">
                    </div>
                </div>
            </div>
            <div style="margin-top: 35px; display: flex; justify-content: flex-end; gap: 12px; padding-bottom:10px;">
                <button type="button" class="btn-cancel" onclick="cerrarModal('modalEmpleado')">Cancelar</button>
                <button type="submit" class="btn-save" id="btnGuardarEmpleado">Registrar Personal</button>
            </div>
        </form>
    </div>
</div>

<div id="modalListaPersonal" class="modal">
    <div class="modal-content" style="width: 70%; max-width: 900px; border:1px solid #dadce0; box-shadow:0 12px 28px rgba(60,64,67,0.12);">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalListaPersonal')">close</span>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="display:flex; align-items:center; gap:10px; margin:0; color:#3c4043;">
                <span class="material-icons">groups</span> Lista de Personal
            </h2>
            <button onclick="cerrarModal('modalListaPersonal'); abrirModal('modalEmpleado')"
<<<<<<< HEAD
                    data-accion="nuevo-empleado"
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
                    style="display:flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%); color:white; border:none; border-radius:50px; cursor:pointer; font-size:14px; font-weight:500; box-shadow:0 2px 8px rgba(26,115,232,0.3); transition:all 0.2s ease; margin-right: 32px;">
                <span class="material-icons" style="font-size:18px;">add</span> Nuevo empleado
            </button>
        </div>
        <div style="margin-bottom: 20px;">
            <input type="text" id="inputBusqueda" placeholder="Buscar por nombre o apellido..." 
                   onkeyup="cargarEmpleados(this.value)" 
                   style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #dadce0; outline:none; background:#f8f9fa;">
        </div>
        <div class="tabla-container" style="max-height: 400px;">
            <table id="tablaEmpleados">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Formación</th>
                        <th style="text-align: right;">Opciones</th> 
                    </tr>
                </thead>
                <tbody id="listaEmpleadosBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalEditarUsuarioLista" class="modal">
    <div class="modal-content" style="max-width: 560px;">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalEditarUsuarioLista')">close</span>
        <h2 style="display:flex; align-items:center; gap:10px; margin-bottom:16px; color:#3c4043;">
            <span class="material-icons">edit</span> Editar Usuario
        </h2>
        <form id="formEditarUsuarioLista" onsubmit="guardarEdicionUsuario(event)">
            <input type="hidden" name="id" id="editarUsuarioId">
            <div class="form-grid">
                <div>
                    <label>Nombre</label>
                    <input type="text" name="nombre" id="editarUsuarioNombre" maxlength="30" required>
                </div>
                <div>
                    <label>Apellido</label>
                    <input type="text" name="apellido" id="editarUsuarioApellido" maxlength="30" required>
                </div>
            </div>
            <label>Username</label>
            <input type="text" name="username" id="editarUsuarioUsername" readonly style="background:#f8f9fa; color:#5f6368; cursor:not-allowed;" placeholder="••••••••••">
            <small style="display:block; margin-top:4px; color:#5f6368; font-size:11px;">El username no puede ser modificado</small>
            <div class="form-grid">
                <div>
                    <label>Correo</label>
                    <input type="email" name="correo" id="editarUsuarioCorreo" maxlength="50">
                </div>
                <div>
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" id="editarUsuarioTelefono" maxlength="15" inputmode="tel" pattern="[0-9+\-]+">
                </div>
            </div>
            <label>Cargo o Formación</label>
            <input type="text" name="formacion" id="editarUsuarioFormacion" maxlength="50">
            <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" class="btn-cancel" onclick="cerrarModal('modalEditarUsuarioLista')">Cancelar</button>
                <button type="submit" class="btn-save">Guardar cambios</button>
            </div>
        </form>
    </div>
</div>

<div id="modalListaUsuarios" class="modal">
    <div class="modal-content" style="width: 70%; max-width: 900px; border:1px solid #dadce0; box-shadow:0 12px 28px rgba(60,64,67,0.12);">
        <span class="material-icons btn-close-modal" onclick="cerrarModal('modalListaUsuarios')">close</span>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2 style="display:flex; align-items:center; gap:10px; margin:0; color:#3c4043;">
                <span class="material-icons">badge</span> Lista de Usuarios
            </h2>
            <button onclick="cerrarModal('modalListaUsuarios'); abrirModal('modalAddUsuario')"
<<<<<<< HEAD
                    data-accion="nuevo-usuario"
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
                    style="display:flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%); color:white; border:none; border-radius:50px; cursor:pointer; font-size:14px; font-weight:500; box-shadow:0 2px 8px rgba(26,115,232,0.3); transition:all 0.2s ease; margin-right: 32px;">
                <span class="material-icons" style="font-size:18px;">add</span> Nuevo usuario
            </button>
        </div>
        <div style="margin-bottom: 20px;">
            <input type="text" id="inputBusquedaUsuarios" placeholder="Buscar por nombre o apellido..."
                   onkeyup="cargarUsuarios(this.value)"
                   style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #dadce0; outline:none; background:#f8f9fa;">
        </div>
        <div class="tabla-container" style="max-height: 400px;">
            <table id="tablaUsuarios">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th style="text-align: right;">Opciones</th>
                    </tr>
                </thead>
                <tbody id="listaUsuariosBody"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
// ============================================================
// MOTOR AUTOCOMPLETE DE EMPLEADOS — Sistema O.S.T.I.
// Reemplaza el select nativo para control total del dropdown.
// ============================================================

/**
 * Obtiene el catálogo completo de empleados del select oculto.
 * @returns {Array<{id: string, label: string}>}
 */
function obtenerCatalogoEmpleados() {
    var catalogo = document.getElementById('emp-catalogo-oculto');
    if (!catalogo) return [];
    var resultado = [];
    for (var i = 0; i < catalogo.options.length; i++) {
        var opt = catalogo.options[i];
        if (opt.value !== '') {
            resultado.push({ id: opt.value, label: opt.text });
        }
    }
    return resultado;
}

/**
 * Devuelve el array de IDs que ya están seleccionados en el contenedor,
 * opcionalmente excluyendo la fila actual.
 * @param {HTMLElement|null} filaExcluir
 * @returns {Array<string>}
 */
function obtenerIdsSeleccionados(filaExcluir) {
    var ids = [];
    var filas = document.querySelectorAll('#contenedor-selects .responsable-row');
    filas.forEach(function(fila) {
        if (fila === filaExcluir) return;
        var hidden = fila.querySelector('.emp-hidden-id');
        if (hidden && hidden.value !== '') ids.push(hidden.value);
    });
    return ids;
}

/**
 * Renderiza la lista de sugerencias en el dropdown de una fila.
 * @param {HTMLElement} fila - La .responsable-row activa
 * @param {string} filtro - Texto de búsqueda
 */
function renderizarDropdown(fila, filtro) {
    var dropdown = fila.querySelector('.emp-dropdown');
    var hiddenInput = fila.querySelector('.emp-hidden-id');
    if (!dropdown) return;

    var todos = obtenerCatalogoEmpleados();
    var yaSeleccionados = obtenerIdsSeleccionados(fila);
    var textoFiltro = (filtro || '').toLowerCase().trim();

    var disponibles = todos.filter(function(emp) {
        var coincideFiltro = textoFiltro === '' || emp.label.toLowerCase().includes(textoFiltro);
        var noEsDuplicado = !yaSeleccionados.includes(emp.id);
        return coincideFiltro && noEsDuplicado;
    });

    if (disponibles.length === 0) {
        dropdown.innerHTML = '<div style="padding:10px 14px; color:#70757a; font-size:13px;">Sin resultados</div>';
        dropdown.style.display = 'block';
        return;
    }

    var html = disponibles.map(function(emp) {
        return '<div class="emp-opcion" data-id="' + emp.id + '" data-label="' + emp.label.replace(/"/g, '&quot;') + '" '
            + 'style="padding:9px 14px; font-size:13px; cursor:pointer; border-bottom:1px solid #f1f3f4; color:#3c4043;" '
            + 'onmousedown="seleccionarEmpleado(this)">' + emp.label + '</div>';
    }).join('');

    dropdown.innerHTML = html;
    dropdown.style.display = 'block';
}

/**
 * Cierra el dropdown de una fila específica.
 */
function cerrarDropdown(fila) {
    var dropdown = fila.querySelector('.emp-dropdown');
    if (dropdown) dropdown.style.display = 'none';
}

/**
 * Cierra TODOS los dropdowns activos.
 */
function cerrarTodosLosDropdowns() {
    document.querySelectorAll('#contenedor-selects .emp-dropdown').forEach(function(d) {
        d.style.display = 'none';
    });
}

/**
 * Selecciona un empleado desde una opción del dropdown.
 * Usa onmousedown para que se dispare antes del blur del input.
 * @param {HTMLElement} opcionEl
 */
function seleccionarEmpleado(opcionEl) {
    var fila = opcionEl.closest('.responsable-row');
    if (!fila) return;
    var searchInput = fila.querySelector('.emp-search-input');
    var hiddenInput = fila.querySelector('.emp-hidden-id');
    if (searchInput) searchInput.value = opcionEl.dataset.label;
    if (hiddenInput) hiddenInput.value = opcionEl.dataset.id;
    cerrarDropdown(fila);
    // Actualizar dropdowns de otras filas para excluir este empleado
    actualizarDropdownsRestantes(fila);
}

/**
 * Tras seleccionar en una fila, refresca las sugerencias de las demás.
 */
function actualizarDropdownsRestantes(filaOrigen) {
    var filas = document.querySelectorAll('#contenedor-selects .responsable-row');
    filas.forEach(function(fila) {
        if (fila === filaOrigen) return;
        var dropdown = fila.querySelector('.emp-dropdown');
        if (dropdown && dropdown.style.display === 'block') {
            var searchInput = fila.querySelector('.emp-search-input');
            renderizarDropdown(fila, searchInput ? searchInput.value : '');
        }
    });
}

/**
 * Crea y devuelve una nueva fila de autocomplete.
 * @param {string} empId - ID del empleado preseleccionado (para modo edición)
 * @param {string} empLabel - Etiqueta del empleado preseleccionado
 * @param {boolean} removible - Mostrar botón eliminar
 * @returns {HTMLElement}
 */
function crearFilaAutocomplete(empId, empLabel, removible) {
    var fila = document.createElement('div');
    fila.className = 'responsable-row';
    fila.style.cssText = 'position:relative; margin-bottom:8px;';

    // Input de búsqueda visible
    var searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'emp-search-input';
    searchInput.placeholder = 'Buscar empleado...';
    searchInput.autocomplete = 'off';
    searchInput.maxLength = 30;
    searchInput.style.cssText = 'width:100%; padding:9px 12px; border:1px solid #dadce0; border-radius:8px; font-size:13px; outline:none; background:#f8f9fa; box-sizing:border-box;';
    if (empLabel) searchInput.value = empLabel;

    // Input oculto para el backend
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'responsable_id[]';
    hiddenInput.className = 'emp-hidden-id';
    if (empId) hiddenInput.value = empId;

    // Contenedor del dropdown
    var dropdown = document.createElement('div');
    dropdown.className = 'emp-dropdown';
    dropdown.style.cssText = 'display:none; position:absolute; z-index:9999; background:#fff; border:1px solid #dadce0; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.12); width:100%; max-height:200px; overflow-y:auto; margin-top:2px; left:0; top:100%;';

    // Eventos del campo de búsqueda
    searchInput.addEventListener('input', function() {
        // Si el usuario borra el texto, limpiar el hidden también
        if (this.value.trim() === '') hiddenInput.value = '';
        renderizarDropdown(fila, this.value);
    });
    searchInput.addEventListener('focus', function() {
        renderizarDropdown(fila, this.value);
    });
    searchInput.addEventListener('blur', function() {
        // Si el texto no corresponde a una selección válida, limpiar
        setTimeout(function() {
            if (!hiddenInput.value) searchInput.value = '';
            cerrarDropdown(fila);
        }, 180);
    });
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') { cerrarDropdown(fila); this.blur(); }
    });

    fila.appendChild(searchInput);
    fila.appendChild(hiddenInput);
    fila.appendChild(dropdown);

    if (removible) {
        var btnRemove = document.createElement('span');
        btnRemove.className = 'material-icons btn-remove';
        btnRemove.textContent = 'remove_circle';
        btnRemove.style.cssText = 'cursor:pointer; color:#5f6368; margin-left:6px; vertical-align:middle; font-size:20px; flex-shrink:0;';
        btnRemove.addEventListener('click', function() {
            fila.remove();
            // Refrescar dropdowns abiertos para re-habilitar el empleado eliminado
            actualizarDropdownsRestantes(null);
        });
        fila.style.display = 'flex';
        fila.style.alignItems = 'center';
        fila.style.gap = '6px';
        fila.appendChild(btnRemove);
    }

    return fila;
}

// Inicializar el contenedor con la primera fila al cargar
document.addEventListener('DOMContentLoaded', function() {
    var contenedor = document.getElementById('contenedor-selects');
    if (contenedor && contenedor.children.length === 0) {
        contenedor.appendChild(crearFilaAutocomplete('', '', false));
    }
    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#contenedor-selects')) {
            cerrarTodosLosDropdowns();
        }
    });
});

function cargarUsuarios(busqueda = '') {
    fetch('auth/listar_usuarios_api.php?busqueda=' + encodeURIComponent(busqueda))
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('listaUsuariosBody');
            tbody.innerHTML = '';
            
            if (data.usuarios && data.usuarios.length > 0) {
                data.usuarios.forEach(u => {
                    const nombreCompleto = u.nombre_completo || '';
                    const partes = nombreCompleto.split(' ');
                    const nombre = partes[0] || 'N/D';
                    const apellido = partes.length > 1 ? partes.slice(1).join(' ') : 'N/D';
                    const username = u.username || 'N/D';
                    const formacion = u.formacion || 'N/D';
                    const correo = u.correo || 'N/D';
                    const telefono = u.telefono || 'N/D';
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${nombre}</td>
                        <td>${apellido}</td>
                        <td style="text-align: right; overflow: visible;">
                            <div class="opciones-menu">
                                <span class="material-icons btn-opciones" role="button" tabindex="0" aria-label="Abrir opciones" aria-expanded="false">more_vert</span>
                                <div class="dropdown-opciones">
                                    <button type="button" onclick="abrirInfoUsuarioDesdeServidor(${u.id})">
                                        <span class="material-icons">info</span> Info
                                    </button>
                                    <button type="button"
                                            onclick="abrirModalEditarUsuarioDesdeLista({
                                                id: '${u.id}',
                                                nombre: '${nombre}',
                                                apellido: '${apellido}',
                                                formacion: '${formacion}',
                                                username: '${username}',
                                                correo: '${correo}',
                                                telefono: '${telefono}'
                                            })">
                                        <span class="material-icons">edit</span> Editar
                                    </button>
<<<<<<< HEAD
                                    <button type="button" onclick="abrirModalRoles(${u.id})">
                                        <span class="material-icons">admin_panel_settings</span> Roles
                                    </button>
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
                                    <button type="button" style="color:#d93025;" onclick="eliminarUsuarioDesdeLista(${u.id})">
                                        <span class="material-icons" style="color:#d93025;">delete</span> Eliminar
                                    </button>
                                </div>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="3" class="mensaje-vacio">No hay resultados</td></tr>';
            }
            
            // Re-inicializar event listeners para dropdowns
            inicializarDropdownsUsuarios();
        })
        .catch(() => {
            document.getElementById('listaUsuariosBody').innerHTML = '<tr><td colspan="3" class="mensaje-vacio">Error al cargar usuarios</td></tr>';
        });
}

function abrirModalEditarUsuarioDesdeLista(usuario) {
    document.getElementById('editarUsuarioId').value = usuario.id || '';
    document.getElementById('editarUsuarioNombre').value = usuario.nombre === 'N/D' ? '' : usuario.nombre;
    document.getElementById('editarUsuarioApellido').value = usuario.apellido === 'N/D' ? '' : usuario.apellido;
    const usernameLength = usuario.username ? usuario.username.length : 0;
    document.getElementById('editarUsuarioUsername').value = usernameLength > 0 ? '•'.repeat(Math.min(usernameLength, 10)) : '';
    document.getElementById('editarUsuarioCorreo').value = usuario.correo === 'N/D' ? '' : usuario.correo;
    document.getElementById('editarUsuarioTelefono').value = usuario.telefono === 'N/D' ? '' : usuario.telefono;
    document.getElementById('editarUsuarioFormacion').value = usuario.formacion === 'N/D' ? '' : usuario.formacion;
    cerrarModal('modalListaUsuarios');
    abrirModal('modalEditarUsuarioLista');
}

function eliminarUsuarioDesdeLista(id) {
<<<<<<< HEAD
    Swal.fire({
        title: '¿Eliminar este usuario?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'osti-swal', confirmButton: 'osti-btn', cancelButton: 'osti-btn-cancel' },
        buttonsStyling: false
    }).then(function (result) {
        if (!result.isConfirmed) return;
        
        const formData = new FormData();
        formData.append('id', id);
        
        fetch('auth/eliminar_usuario_admin.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.ok) {
                    cargarUsuarios(document.getElementById('inputBusquedaUsuarios').value);
                } else {
                    mostrarToastPersonalizado(data.message || 'No se pudo eliminar el usuario', 'error');
                }
            })
            .catch(() => mostrarToastPersonalizado('No se pudo eliminar el usuario', 'error'));
    });
=======
    if (!confirm('¿Estás seguro de eliminar este usuario?')) return;
    
    const formData = new FormData();
    formData.append('id', id);
    
    fetch('auth/eliminar_usuario_admin.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                cargarUsuarios(document.getElementById('inputBusquedaUsuarios').value);
            } else {
                alert(data.message || 'No se pudo eliminar el usuario');
            }
        })
        .catch(() => alert('No se pudo eliminar el usuario'));
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
}

function abrirInfoUsuarioDesdeServidor(id) {
    fetch('auth/obtener_usuario.php?id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.ok && data.usuario) {
                const u = data.usuario;
                const nombreCompleto = u.nombre_completo || 'N/D';
                const partes = nombreCompleto.split(' ');
                const nombre = partes[0] || 'N/D';
                const apellido = partes.length > 1 ? partes.slice(1).join(' ') : 'N/D';
                const username = u.username || 'N/D';
                const formacion = u.formacion || 'N/D';
                const correo = u.correo || 'N/D';
                const telefono = u.telefono || 'N/D';
                const rol = u.rol || 'N/D';
                
                const modalInfoUsuario = document.createElement('div');
                modalInfoUsuario.id = 'modalInfoUsuario';
                modalInfoUsuario.className = 'modal';
                modalInfoUsuario.style.display = 'block';
                modalInfoUsuario.style.zIndex = '2000';
                modalInfoUsuario.innerHTML = `
                    <div class="modal-content" style="max-width: 500px;">
                        <span class="material-icons btn-close-modal" onclick="this.closest('.modal').remove()">close</span>
                        <h2 style="display:flex; align-items:center; gap:10px; margin-bottom:20px; color:#3c4043;">
                            <span class="material-icons">info</span> Información de Usuario
                        </h2>
                        <div style="display:grid; gap:12px;">
                            <div><strong>Nombre:</strong> ${nombre}</div>
                            <div><strong>Apellido:</strong> ${apellido}</div>
                            <div><strong>Username:</strong> ${username}</div>
                            <div><strong>Formación:</strong> ${formacion}</div>
                            <div><strong>Correo:</strong> ${correo}</div>
                            <div><strong>Teléfono:</strong> ${telefono}</div>
                            <div><strong>Rol:</strong> ${rol}</div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modalInfoUsuario);
                
                modalInfoUsuario.addEventListener('click', function(e) {
                    if (e.target === modalInfoUsuario) {
                        modalInfoUsuario.remove();
                    }
                });
            }
        })
        .catch(() => console.log('Error al obtener información del usuario'));
}

function guardarEdicionUsuario(e) {
    e.preventDefault();
<<<<<<< HEAD
    var form = e.target;
    var nombre = (form.querySelector('[name="nombre"]') || {}).value || '';
    var apellido = (form.querySelector('[name="apellido"]') || {}).value || '';
    if (/\d/.test(nombre) || /\d/.test(apellido)) {
        mostrarToastPersonalizado('El nombre y apellido no pueden contener números.', 'error');
        return;
    }
    const formData = new FormData(form);
=======
    const formData = new FormData(e.target);
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
    fetch('auth/actualizar_usuario_admin.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                cerrarModal('modalEditarUsuarioLista');
<<<<<<< HEAD
                location.reload();
            } else {
                Swal.fire({
                    icon: 'error', title: data.message || 'No se pudo actualizar el usuario',
                    confirmButtonText: 'Aceptar',
                    customClass: { popup: 'osti-swal', confirmButton: 'osti-btn' },
                    buttonsStyling: false
                });
            }
        })
        .catch(() => mostrarToastPersonalizado('No se pudo actualizar el usuario', 'error'));
=======
                cargarUsuarios(document.getElementById('inputBusquedaUsuarios').value);
            } else {
                console.log(data.message || 'No se pudo actualizar el usuario');
            }
        })
        .catch(() => console.log('No se pudo actualizar el usuario'));
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
}

function inicializarDropdownsUsuarios() {
    const dropdowns = document.querySelectorAll('#tablaUsuarios .opciones-menu');
    dropdowns.forEach(dropdown => {
        const btn = dropdown.querySelector('.btn-opciones');
        const menu = dropdown.querySelector('.dropdown-opciones');
        
        if (btn && menu) {
            btn.onclick = function(e) {
                e.stopPropagation();
<<<<<<< HEAD
                const isOpen = menu.classList.contains('show');

                document.querySelectorAll('#tablaUsuarios .dropdown-opciones.show').forEach(m => {
                    m.classList.remove('show');
                    m.style.position = '';
                    m.style.top = '';
                    m.style.right = '';
                });

                if (!isOpen) {
                    const rect = btn.getBoundingClientRect();
                    menu.style.position = 'fixed';
                    menu.style.top = rect.bottom + 'px';
                    menu.style.right = (document.documentElement.clientWidth - rect.right) + 'px';
                    menu.classList.add('show');
                    btn.setAttribute('aria-expanded', 'true');
                } else {
                    btn.setAttribute('aria-expanded', 'false');
                }
=======
                menu.classList.toggle('show');
                btn.setAttribute('aria-expanded', menu.classList.contains('show') ? 'true' : 'false');
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
            };
        }
    });
}

<<<<<<< HEAD
function cerrarDropdownOpciones() {
    document.querySelectorAll('.dropdown-opciones.show').forEach(m => {
        m.classList.remove('show');
        m.style.position = '';
        m.style.top = '';
        m.style.right = '';
    });
}

=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
// Cargar usuarios al abrir el modal
document.addEventListener('DOMContentLoaded', function() {
    const modalUsuarios = document.getElementById('modalListaUsuarios');
    if (modalUsuarios) {
        modalUsuarios.addEventListener('click', function(e) {
            if (e.target === modalUsuarios) {
                cerrarModal('modalListaUsuarios');
            }
        });
    }
<<<<<<< HEAD

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.btn-opciones')) {
            cerrarDropdownOpciones();
        }
    });
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
});

// Exclusión de preguntas de seguridad
function actualizarPreguntasDisponibles() {
    const selects = document.querySelectorAll('select[name^="pregunta_"]');
    const preguntasSeleccionadas = Array.from(selects).map(select => select.value).filter(val => val !== '');
    
    selects.forEach(select => {
        const valorActual = select.value;
        const opciones = select.querySelectorAll('option');
        
        opciones.forEach(opcion => {
            if (opcion.value !== '') {
                // Si esta pregunta está seleccionada en otro select y no es el select actual
                if (preguntasSeleccionadas.includes(opcion.value) && opcion.value !== valorActual) {
                    opcion.disabled = true;
                } else {
                    opcion.disabled = false;
                }
            }
        });
    });
}

// Agregar event listeners a los selects de preguntas
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name^="pregunta_"]');
    selects.forEach(select => {
        select.addEventListener('change', actualizarPreguntasDisponibles);
    });
});
</script>

<!-- Toast de límite de caracteres + Validación de teléfono -->
<style>
    .osti-toast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(80px);
        background: #323336;
        color: #fff;
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        z-index: 99999;
        box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
    }
    .osti-toast.visible {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
</style>
<script>
(function() {
    // ── Toast notification para límite de caracteres ──
    var toastEl = document.createElement('div');
    toastEl.className = 'osti-toast';
    toastEl.textContent = 'Límite de caracteres alcanzado';
    document.body.appendChild(toastEl);

    var toastTimer = null;
    function mostrarToast() {
        toastEl.classList.add('visible');
        clearTimeout(toastTimer);
        toastTimer = setTimeout(function() {
            toastEl.classList.remove('visible');
        }, 2000);
    }

    // Escuchar input en campos con maxlength para mostrar Toast
    document.addEventListener('input', function(e) {
        var el = e.target;
        if (!el.matches('input, textarea')) return;
        var max = parseInt(el.getAttribute('maxlength'), 10);
        if (!max || isNaN(max)) return;
        if (el.value.length >= max) {
            mostrarToast();
        }
    });

    // Bloqueo de teclado al llegar al límite
    document.addEventListener('keydown', function(e) {
        var el = e.target;
        if (!el.matches('input, textarea')) return;
        var max = parseInt(el.getAttribute('maxlength'), 10);
        if (!max || isNaN(max)) return;
        
        // Teclas que siempre permitimos (borrar, flechas, control)
        var permitidas = ['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown', 'Tab', 'Home', 'End'];
        if (permitidas.includes(e.key) || e.ctrlKey || e.metaKey) return;

        if (el.value.length >= max) {
            var start = el.selectionStart;
            var end = el.selectionEnd;
            // Si hay texto seleccionado, se puede reemplazar, así que permitimos
            if (start !== end) return;
            
            e.preventDefault();
            mostrarToast();
        }
    });

    // Validación de teléfono: solo números, +, -
    document.addEventListener('input', function(e) {
        var el = e.target;
        if (el.type !== 'tel') return;
        var limpio = el.value.replace(/[^0-9+\-]/g, '');
        if (limpio !== el.value) {
            el.value = limpio;
        }
    });
})();
</script>

<script src="assets/js/dashboard/core-ui.js"></script>
<script src="assets/js/dashboard/empleados-actividades.js"></script>
<<<<<<< HEAD
<script src="assets/js/dashboard/permisos.js"></script>
=======
>>>>>>> 2f72d4b40d0d173209acf2d06dc5345c872ff938
<script src="assets/js/dashboard/init.js"></script>