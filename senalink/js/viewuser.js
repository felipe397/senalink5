document.addEventListener("DOMContentLoaded", function () {
    fetch('http://localhost/senalink5/senalink/controllers/UsuarioController.php?action=detalleUsuario', {
            credentials: 'include'
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error(data.error);
                return;
            }

            // Mostrar datos para EMPRESA
            if (data.rol === 'empresa') {
                document.getElementById('empresa-section').style.display = 'block';
                document.getElementById('nit').textContent = data.nit || '';
                document.getElementById('razon_social').textContent = data.razon_social || '';
                document.getElementById('representante_legal').textContent = data.representante_legal || '';
                document.getElementById('tipo_empresa').textContent = data.tipo_empresa || '';
                document.getElementById('telefono_empresa').textContent = data.telefono || '';
                document.getElementById('correo_empresa').textContent = data.correo || '';
                document.getElementById('direccion_empresa').textContent = data.direccion || '';
                document.getElementById('estado').textContent = data.estado || '';
            }

            // Mostrar datos para SUPER_ADMIN
            else if (data.rol === 'super_admin') {
                document.getElementById('admin-section').style.display = 'block';
                const nombreCompleto = `${data.primer_nombre || ''} ${data.segundo_nombre || ''}`.trim();
                const apellidoCompleto = `${data.primer_apellido || ''} ${data.segundo_apellido || ''}`.trim();
                document.getElementById('nombre_admin').textContent = nombreCompleto;
                document.getElementById('apellido_admin').textContent = apellidoCompleto;
                document.getElementById('correo_admin').textContent = data.correo || '';
                document.getElementById('telefono_admin').textContent = data.telefono || '';
            }

            // Si el rol no es reconocido
            else {
                console.error("Rol no reconocido:", data.rol);
            }
        })
        .catch(error => {
            console.error("Error al cargar los datos del usuario:", error);
        });
});
