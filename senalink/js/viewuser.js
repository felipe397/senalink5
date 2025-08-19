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

        // Asignar el id al enlace de actualizar
        const enlaceActualizar = document.getElementById('enlaceActualizar');
        if (enlaceActualizar && userData.id) {
            enlaceActualizar.href = `../html/EditFuncionario.html?id=${userData.id}`;
        }
    })
    .catch(error => {
        console.error("Error en la solicitud:", error);
    });
});
