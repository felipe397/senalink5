document.addEventListener("DOMContentLoaded", function () {
    fetch('http://localhost/senalink5/senalink5/senalink/controllers/UsuarioController.php?action=detalleUsuario', {
        credentials: 'include'
    })
    .then(response => response.text())
    .then(text => {
        console.log("📥 Respuesta del servidor:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("❌ La respuesta no es JSON válido:", text);
            return;
        }

        if (data.error) {
            console.error("🚫 Error del backend:", data.error);
            return;
        }

        const userData = data.data || data;

        // Mostrar datos para EMPRESA
        if (userData.rol === 'empresa') {
            document.getElementById('empresa-section').style.display = 'block';
            document.getElementById('nit').textContent = userData.nit || '';
            document.getElementById('razon_social').textContent = userData.razon_social || '';
            document.getElementById('representante_legal').textContent = userData.representante_legal || '';
            document.getElementById('tipo_empresa').textContent = userData.tipo_empresa || '';
            document.getElementById('telefono_empresa').textContent = userData.telefono || '';
            document.getElementById('correo_empresa').textContent = userData.correo || '';
            document.getElementById('direccion_empresa').textContent = userData.direccion || '';
            document.getElementById('estado').textContent = userData.estado || '';

            // Mostrar/ocultar botones según estado
            const btnActualizar = document.getElementById('btnActualizarEmpresa');
            const btnReporte = document.getElementById('btnReporteEmpresa');
            if (userData.estado && userData.estado.trim() === 'Desactivado') {
                if (btnActualizar) btnActualizar.style.display = 'none';
                if (btnReporte) btnReporte.style.display = 'none';
            } else {
                if (btnActualizar) btnActualizar.style.display = '';
                if (btnReporte) btnReporte.style.display = '';
            }
        }

        // Mostrar datos para SUPER_ADMIN y AdminSENA
        else if (userData.rol === 'super_admin' || userData.rol === 'AdminSENA') {
            document.getElementById('admin-section').style.display = 'block';
            const nombreCompleto = `${userData.primer_nombre || ''} ${userData.segundo_nombre || ''}`.trim();
            const apellidoCompleto = `${userData.primer_apellido || ''} ${userData.segundo_apellido || ''}`.trim();
            document.getElementById('nombre_admin').textContent = nombreCompleto;
            document.getElementById('apellido_admin').textContent = apellidoCompleto;
            document.getElementById('correo_admin').textContent = userData.correo || '';
            document.getElementById('telefono_admin').textContent = userData.telefono || '';
        } else {
            console.error("Rol no reconocido:", userData.rol);
        }

        // Redirigir al formulario de edición correcto según el rol
        const btnActualizar = document.querySelector('.actions .btn.edit');
        if (btnActualizar && userData.id) {
            let url = '';
            if (userData.rol === 'empresa') {
                url = `../html/EditEmpresa.html?id=${userData.id}`;
            } else if (userData.rol === 'AdminSENA') {
                url = `../html/EditFuncionario.html?id=${userData.id}`;
            } else if (userData.rol === 'super_admin') {
                url = `../html/EditSuper_Admin.html?id=${userData.id}`;
            }
            btnActualizar.onclick = function() {
                window.location.href = url;
            };
        }

        // --- Cierre de sesión por inactividad según rol ---
        // Requiere que userData.rol esté disponible en el scope global o accesible tras login
        let inactividadTimeout;
        let tiempoMaxInactividad = 600000; // default 10 min
        let mensajeInactividad = 'Tu sesión ha finalizado por inactividad.';

        function definirTiempoInactividad(rol) {
            if (rol === 'empresa') {
                tiempoMaxInactividad = 15 * 60 * 1000; // 15 minutos
                mensajeInactividad = 'Tu sesión de empresa ha finalizado por inactividad.';
            } else if (rol === 'super_admin' || rol === 'AdminSENA') {
                tiempoMaxInactividad = 10 * 60 * 1000; // 10 minutos
                mensajeInactividad = 'Tu sesión de administrador ha finalizado por inactividad.';
            }
        }

        function cerrarSesionPorInactividad() {
            // Mostrar alerta usando showAlert de alert.js
            if (typeof showAlert === 'function') {
                showAlert(mensajeInactividad, 'warning');
            } else {
                alert(mensajeInactividad);
            }
            setTimeout(function() {
                window.location.href = '../html/index.html';
            }, 1500);
            // Aquí podrías limpiar storage/cookies si es necesario
        }

        function reiniciarTemporizadorInactividad() {
            clearTimeout(inactividadTimeout);
            inactividadTimeout = setTimeout(cerrarSesionPorInactividad, tiempoMaxInactividad);
        }

        // Inicializar control de inactividad tras obtener el rol
        // Llama a esto después de obtener userData.rol
        function iniciarControlInactividad(rol) {
            definirTiempoInactividad(rol);
            ['mousemove', 'keydown', 'scroll', 'click'].forEach(function(evt) {
                window.addEventListener(evt, reiniciarTemporizadorInactividad);
            });
            reiniciarTemporizadorInactividad();
        }

        iniciarControlInactividad(userData.rol);
        // --- Fin cierre de sesión por inactividad ---
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
    });
});
