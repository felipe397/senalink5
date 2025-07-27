document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const usuarioId = params.get('id');
    if (!usuarioId) {
        alert('No se proporcionó el ID del funcionario.');
        return;
    }

    fetch(`http://localhost/senalink5/senalink5/senalink/controllers/UsuarioController.php?action=detalleUsuario&id=${usuarioId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const usuario = data.data;
                document.getElementById('primer_nombre').textContent = usuario.primer_nombre || '';
                document.getElementById('segundo_nombre').textContent = usuario.segundo_nombre || '';
                document.getElementById('primer_apellido').textContent = usuario.primer_apellido || '';
                document.getElementById('segundo_apellido').textContent = usuario.segundo_apellido || '';
                document.getElementById('correo').textContent = usuario.correo || '';
                document.getElementById('telefono').textContent = usuario.telefono || '';
                document.getElementById('numero_documento').textContent = usuario.numero_documento || '';

                // Validar que tipo_documento sea solo cedula de ciudadania o cedula de extranjeria para AdminSENA
                const tiposPermitidos = ['Cédula de ciudadania', 'Cédula de extranjería'];
                if (!tiposPermitidos.includes(usuario.tipo_documento)) {
                    document.getElementById('tipo_documento').textContent = 'Tipo de documento no permitido';
                } else {
                    document.getElementById('tipo_documento').textContent = usuario.tipo_documento || '';
                }

                document.getElementById('estado').textContent = usuario.estado || '';

                // Preparar formulario para generar reporte PDF
                const formReporte = document.getElementById('formReporte');
                if (formReporte) {
                    formReporte.elements['primer_nombre'].value = usuario.primer_nombre || '';
                    formReporte.elements['segundo_nombre'].value = usuario.segundo_nombre || '';
                    formReporte.elements['primer_apellido'].value = usuario.primer_apellido || '';
                    formReporte.elements['segundo_apellido'].value = usuario.segundo_apellido || '';
                    formReporte.elements['correo'].value = usuario.correo || '';
                    formReporte.elements['telefono'].value = usuario.telefono || '';
                    formReporte.elements['numero_documento'].value = usuario.numero_documento || '';
                    formReporte.elements['tipo_documento'].value = tiposPermitidos.includes(usuario.tipo_documento) ? usuario.tipo_documento : '';
                    formReporte.elements['estado'].value = usuario.estado || '';
                }
            } else {
                alert('No se encontró el funcionario.');
            }
        })
        .catch(error => {
            console.error('Error al obtener datos del funcionario:', error);
            alert('Error al obtener datos del funcionario.');
        });

    // Evento para botón descargar PDF
    const btnReport = document.querySelector('.btn__report');
    if (btnReport) {
        btnReport.addEventListener('click', () => {
            const formReporte = document.getElementById('formReporte');
            if (formReporte) {
                formReporte.submit();
            }
        });
    }
});
