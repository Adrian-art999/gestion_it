(function () {
    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-opciones').forEach(function (menu) {
            menu.classList.remove('show');
        });
        document.querySelectorAll('.btn-opciones').forEach(function (btn) {
            btn.setAttribute('aria-expanded', 'false');
        });
    }

    function abrirModal(id) {
        var modal = document.getElementById(id);
        if (!modal) return;

        modal.style.display = 'block';
        closeAllDropdowns();

        if (id === 'modalListaPersonal' && typeof window.cargarEmpleados === 'function') {
            window.cargarEmpleados('');
        }
        if (id === 'modalActividad' && typeof window.setModoActividad === 'function') {
            window.setModoActividad(false);
        }
    }

    function cerrarModal(id) {
        var modal = document.getElementById(id);
        if (!modal) return;

        var form = modal.querySelector('form');
        if (form) {
            form.reset();
            var inputId = form.querySelector('input[name="id_empleado"]');
            if (inputId) inputId.remove();
        }

        if (id === 'modalEmpleado') {
            var tituloEmpleado = document.getElementById('tituloEmpleado');
            var btnGuardarEmpleado = document.getElementById('btnGuardarEmpleado');
            var divGmail = document.getElementById('div_gmail');
            var divTel = document.getElementById('div_tel');
            if (tituloEmpleado) tituloEmpleado.innerText = 'Registrar Personal';
            if (btnGuardarEmpleado) btnGuardarEmpleado.innerText = 'Registrar Personal';
            if (divGmail) divGmail.style.display = 'none';
            if (divTel) divTel.style.display = 'none';
        }

        if (id === 'modalActividad') {
            if (typeof window.setModoActividad === 'function') {
                window.setModoActividad(false);
            }
            var areaManual = document.getElementById('m_area');
            if (areaManual) areaManual.style.display = 'none';
        }

        if (id === 'modalAddUsuario') {
            var gmail = document.getElementById('div_usuario_gmail');
            var tel = document.getElementById('div_usuario_tel');
            if (gmail) {
                gmail.style.display = 'none';
                var gmailInput = gmail.querySelector('input');
                if (gmailInput) {
                    gmailInput.value = '';
                    gmailInput.required = false;
                }
            }
            if (tel) {
                tel.style.display = 'none';
                var telInput = tel.querySelector('input');
                if (telInput) {
                    telInput.value = '';
                    telInput.required = false;
                }
            }
        }

        modal.style.display = 'none';
    }

    function toggleInput(select, idDiv) {
        var div = document.getElementById(idDiv);
        if (!div) return;
        var input = div.querySelector('input');
        if (!input) return;

        if (select.value === 'si') {
            div.style.display = 'block';
            input.required = true;
        } else {
            div.style.display = 'none';
            input.value = '';
            input.required = false;
        }
    }

    function initSecurityQuestions(scopeEl) {
        var scope = scopeEl || document;
        var selects = Array.prototype.slice.call(scope.querySelectorAll('select[name^="pregunta_"]'));
        if (selects.length < 2) return;

        function refresh() {
            var selected = selects
                .map(function (s) { return s.value; })
                .filter(function (v) { return v && v.trim() !== ''; });

            selects.forEach(function (sel) {
                Array.prototype.slice.call(sel.options).forEach(function (opt) {
                    if (!opt.value) return;
                    opt.disabled = (opt.value !== sel.value && selected.indexOf(opt.value) !== -1);
                });
            });
        }

        selects.forEach(function (sel) {
            sel.addEventListener('change', refresh);
        });
        refresh();
    }

    function abrirInfoPerfil(datos) {
        var safe = function (val) {
            return val && String(val).trim() !== '' ? String(val) : 'N/D';
        };
        var usernameRow = document.getElementById('perfilInfoUsernameRow');
        if (usernameRow) {
            usernameRow.style.display = datos.esEmpleado ? 'none' : 'block';
        }

        var nombre = document.getElementById('perfilInfoNombre');
        var apellido = document.getElementById('perfilInfoApellido');
        var formacion = document.getElementById('perfilInfoFormacion');
        var username = document.getElementById('perfilInfoUsername');
        var correo = document.getElementById('perfilInfoCorreo');
        var telefono = document.getElementById('perfilInfoTelefono');

        if (nombre) nombre.innerText = safe(datos.nombre);
        if (apellido) apellido.innerText = safe(datos.apellido);
        if (formacion) formacion.innerText = safe(datos.formacion);
        if (username) username.innerText = safe(datos.username);
        if (correo) correo.innerText = safe(datos.correo);
        if (telefono) telefono.innerText = safe(datos.telefono);

        abrirModal('modalInfoPerfil');
    }

    function bindGlobalUiEvents() {
        window.addEventListener('click', function (event) {
            if (event.target.classList && event.target.classList.contains('modal')) {
                cerrarModal(event.target.id);
            }

            if (!event.target.closest('.btn-opciones') && !event.target.closest('.dropdown-opciones')) {
                closeAllDropdowns();
            }
        });
    }

    window.closeAllDropdowns = closeAllDropdowns;
    window.abrirModal = abrirModal;
    window.cerrarModal = cerrarModal;
    window.toggleInput = toggleInput;
    window.initSecurityQuestions = initSecurityQuestions;
    window.abrirInfoPerfil = abrirInfoPerfil;
    window.bindGlobalUiEvents = bindGlobalUiEvents;
})();
