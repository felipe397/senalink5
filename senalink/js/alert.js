document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('forgotForm');
    const messageEl = document.getElementById('message');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const email = document.getElementById('emailInput').value.trim();

        if (!email) {
            messageEl.style.color = 'red';
            messageEl.textContent = 'Por favor ingrese un correo válido.';
            return;
        }

        fetch('../controllers/send_code.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `correo=${encodeURIComponent(email)}`
        })
        .then(response => response.json())
        .then(data => {
            messageEl.style.display = 'block';

            if (data.success) {
                messageEl.style.color = 'green';
                messageEl.textContent = 'El link ha sido enviado exitosamente.';
            } else {
                messageEl.style.color = 'red';
                switch (data.error) {
                    case 'correo_invalido':
                        messageEl.textContent = 'Por favor ingrese un correo válido.';
                        break;
                    case 'correo_no_registrado':
                        messageEl.textContent = 'El correo ingresado no está registrado.';
                        break;
                    case 'envio_fallido':
                        messageEl.textContent = 'No se pudo enviar el correo. Intente más tarde.';
                        break;
                    case 'error_servidor':
                    default:
                        messageEl.textContent = 'Ocurrió un error inesperado. Intente nuevamente.';
                        break;
                }
            }
        })
        .catch(() => {
            messageEl.style.color = 'red';
            messageEl.textContent = 'Error de conexión con el servidor.';
        });
    });
});
