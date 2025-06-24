document.addEventListener("DOMContentLoaded", function () {
    const btnInhabilitar = document.getElementById('btn-inhabilitar');
    if (btnInhabilitar) {
        console.log('Botón inhabilitar encontrado, asignando evento click');
        btnInhabilitar.addEventListener('click', function () {
            console.log('Click en botón inhabilitar');
            const empresaId = this.dataset.empresaId;
            console.log('ID empresa:', empresaId);
            if (!empresaId || empresaId.trim() === '') {
                console.error('ID de empresa no encontrado o vacío');
                return;
            }

            // Solo mostrar el modal al hacer click, no al cargar la página
            if (document.readyState === 'complete') {
                console.log('Mostrando modal de confirmación');
                confirmInhabilitar(function () {
                fetch('../../../../controllers/updateEstadoEmpresa.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id=${empresaId}&estado=inactivo`
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Respuesta del servidor:', data);
                        if (data.success) {
                            alert('Empresa inhabilitada correctamente');
                            // Redirigir a gestion_empresa.html para ver lista actualizada
                            window.location.href = 'Gestion_Empresa.html';
                        } else {
                            alert('Error al inhabilitar empresa: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error en la petición:', error);
                        alert('Error en la petición para inhabilitar empresa');
                    });
                });
            }
        });
    } else {
        console.warn('Botón inhabilitar no encontrado');
    }
});
