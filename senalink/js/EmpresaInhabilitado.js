document.addEventListener("DOMContentLoaded", function () {
    const btnHabilitar = document.getElementById('btn-habilitar');
    const empresaIdFromUrl = new URLSearchParams(window.location.search).get('id');

    function habilitarEmpresa(empresaId) {
        fetch("../../../controllers/updateEstadoEmpresa.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${empresaId}&estado=activo`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Empresa habilitada correctamente');
                // Si estamos en detalle, redirigir a la lista
                if (window.location.pathname.includes('EmpresaInhabilitado.html')) {
                    window.location.href = 'Gestion_Empresa.html';
                } else {
                    // Si estamos en lista, recargar la página para actualizar la lista
                    location.reload();
                }
            } else {
                alert('Error al habilitar empresa: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error en la petición para habilitar empresa');
        });
    }

    // Si hay botón habilitar y estamos en detalle (EmpresaInhabilitado.html)
    if (btnHabilitar && empresaIdFromUrl) {
        btnHabilitar.dataset.empresaId = empresaIdFromUrl;
        btnHabilitar.addEventListener('click', function () {
            const empresaId = this.dataset.empresaId;
            habilitarEmpresa(empresaId);
        });
    }

    // Si estamos en lista de empresas inhabilitadas (Gestion_Empresas_inhabilitadas.html)
    const botonesHabilitar = document.querySelectorAll('.btn-habilitar');
    if (botonesHabilitar.length > 0) {
        botonesHabilitar.forEach(boton => {
            boton.addEventListener('click', function () {
                const empresaId = this.dataset.empresaId;
                if (!empresaId) {
                    console.error('ID de empresa no encontrado');
                    return;
                }
                if (confirm('¿Está seguro que desea habilitar esta empresa?')) {
                    habilitarEmpresa(empresaId);
                }
            });
        });
    }

    // Mostrar advertencia en login si la empresa está inhabilitada
    function mostrarAdvertenciaEmpresaInhabilitada() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('empresa_inhabilitada') === '1') {
            const advertDiv = document.getElementById('empresa-inhabilitada-advertencia');
            const mensaje = '⚠️ Su empresa está inhabilitada. Por favor contacte al administrador.';
            if (advertDiv) {
                advertDiv.textContent = mensaje;
                advertDiv.style.display = 'block';
            } else {
                // Fallback: crear el div si no existe (por si lo llaman en otra vista)
                let advert = document.createElement('div');
                advert.textContent = mensaje;
                advert.style.color = '#c0392b';
                advert.style.fontWeight = 'bold';
                advert.style.textAlign = 'center';
                advert.style.margin = '1.5rem 0 0 0';
                advert.style.fontSize = '1.5rem';
                const loginContainer = document.querySelector('.container__forms--login');
                if (loginContainer) {
                    loginContainer.prepend(advert);
                }
            }
            alert(mensaje);
        }
    }
    document.addEventListener('DOMContentLoaded', mostrarAdvertenciaEmpresaInhabilitada);
});
