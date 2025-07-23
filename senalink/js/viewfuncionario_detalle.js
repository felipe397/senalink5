// Script para habilitar/inhabilitar usuario AdminSENA desde el detalle

document.addEventListener('DOMContentLoaded', () => {
    const usuarioId = new URLSearchParams(window.location.search).get('id');
    const btnInhabilitar = document.getElementById('btn-inhabilitar');
    const btnHabilitar = document.getElementById('btn-habilitar');
    const estadoSpan = document.getElementById('estado');
    // Buscar el botón de actualizar por id o clase
    const btnActualizar = document.getElementById('linkActualizar') || document.querySelector('a.buttons__crud[href="#"]');
    const btnReporte = document.getElementById('btnReporte');

    // Ocultar/mostrar botones según estado
    function actualizarBotonesPorEstado() {
        const estado = estadoSpan.textContent.trim();
        if (estado === 'Desactivado') {
            if (btnActualizar) btnActualizar.style.display = 'none';
            if (btnReporte) btnReporte.style.display = 'none';
            if (btnInhabilitar) {
                btnInhabilitar.textContent = 'Habilitar';
                btnInhabilitar.classList.remove('btn-inhabilitar');
                btnInhabilitar.classList.add('btn-habilitar');
            }
        } else {
            if (btnActualizar) btnActualizar.style.display = '';
            if (btnReporte) btnReporte.style.display = '';
            if (btnInhabilitar) {
                btnInhabilitar.textContent = 'Inhabilitar';
                btnInhabilitar.classList.remove('btn-habilitar');
                btnInhabilitar.classList.add('btn-inhabilitar');
            }
        }
    }

    // Llamar al actualizarBotonesPorEstado después de cargar los datos del usuario
    setTimeout(actualizarBotonesPorEstado, 500); // Espera a que el fetch del HTML cargue el estado

    function confirmInhabilitar(callback) {
        const modal = document.getElementById("custom-confirm");
        modal.classList.add("show");
        document.getElementById("confirm-yes").onclick = function () {
            modal.classList.remove("show");
            if (typeof callback === "function") callback();
        };
        document.getElementById("confirm-no").onclick = function () {
            modal.classList.remove("show");
        };
    }

    if (btnInhabilitar && usuarioId) {
        btnInhabilitar.addEventListener('click', function () {
            const estadoActual = estadoSpan.textContent.trim();
            const nuevoEstado = estadoActual === 'Activo' ? 'Desactivado' : 'Activo';
            confirmInhabilitar(() => {
                const formData = new URLSearchParams();
                formData.append('accion', 'actualizarEstado');
                formData.append('id', usuarioId);
                formData.append('estado', nuevoEstado);
                fetch('../../../controllers/UsuarioController.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                })
                    .then(response => response.json())
.then(data => {
    if (data.success) {
        showAlert('Estado actualizado correctamente', 'success');
        setTimeout(() => {
            window.location.href = 'Gestion_Funcionario.html';
        }, 1500);
    } else {
        alert(data.error || 'Error al actualizar estado');
    }
})
.catch(error => {
    alert('Error al actualizar estado');
});
            });
        });
    }

    if (btnHabilitar && usuarioId) {
        btnHabilitar.addEventListener('click', function () {
            const formData = new URLSearchParams();
            formData.append('accion', 'actualizarEstado');
            formData.append('id', usuarioId);
            formData.append('estado', 'Activo');
            fetch('../../../controllers/UsuarioController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
                .then(response => response.json())
.then(data => {
    if (data.success) {
        showAlert('Estado actualizado correctamente', 'success');
        setTimeout(() => {
            window.location.href = 'Gestion_Funcionario.html';
        }, 1500);
    } else {
        alert(data.error || 'Error al actualizar estado');
    }
})
.catch(error => {
    alert('Error al actualizar estado');
});
        });
    }
});
