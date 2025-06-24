document.addEventListener("DOMContentLoaded", function () {
    function confirmInhabilitar(callback) {
        const modal = document.getElementById('custom-confirm');
        if (!modal) {
            console.error('Modal de confirmación no encontrado');
            return;
        }
        modal.classList.add('show');

        const btnYes = document.getElementById('confirm-yes');
        const btnNo = document.getElementById('confirm-no');

        function closeModal() {
            modal.classList.remove('show');
            btnYes.removeEventListener('click', onYes);
            btnNo.removeEventListener('click', onNo);
        }

        function onYes() {
            closeModal();
            callback();
        }

        function onNo() {
            closeModal();
        }

        btnYes.addEventListener('click', onYes);
        btnNo.addEventListener('click', onNo);
    }

    const botones = document.querySelectorAll('.btn-inhabilitar');
    botones.forEach(boton => {
        boton.addEventListener('click', function () {
            const empresaId = this.dataset.empresaId;
            if (!empresaId) {
                console.error('ID de empresa no encontrado');
                return;
            }
            confirmInhabilitar(function () {
                fetch('../../../controllers/updateEstadoEmpresa.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${empresaId}&estado=inactivo`
                })
                .then(response => response.text())
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            alert('Empresa inhabilitada correctamente');
                            // Recargar la página para actualizar la lista
                            location.reload();
                        } else {
                            alert('Error al inhabilitar empresa: ' + data.message);
                        }
                    } catch (e) {
                        console.error('Respuesta no es JSON válido:', text);
                        alert('Error en la respuesta del servidor al inhabilitar empresa');
                    }
                })
                .catch(error => {
                    console.error('Error en la petición:', error);
                    alert('Error en la petición para inhabilitar empresa');
                });
            });
        });
    });
});
