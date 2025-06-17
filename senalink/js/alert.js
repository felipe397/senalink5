    document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('forgotForm');
  const messageEl = document.getElementById('message');

  form.addEventListener('submit', function(event) {
    event.preventDefault();
    const email = document.getElementById('emailInput').value.trim();

    if (!email) {
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
        if (data.success) {
            messageEl.style.color = 'green';
            messageEl.textContent = 'Código enviado al correo. Por favor revise su bandeja.';
            setTimeout(() => {
                window.location.href = 'Fogot_code.html';
            }, 2000);
        } else {
            messageEl.style.color = 'red';
            messageEl.textContent = data.message || 'Error al enviar el código.';
        }
    })
    .catch(error => {
        messageEl.style.color = 'red';
        messageEl.textContent = 'Error en la conexión.';
    });
  });
});