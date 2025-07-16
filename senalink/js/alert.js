// Función reutilizable para mostrar alertas personalizadas en cualquier HTML
// Uso: showAlert('Mensaje', 'success'|'error'|'warning', selectorDondeInsertar)

function showAlert(message, type = 'error', insertBeforeSelector = '.container') {
  // Elimina alertas previas
  const prev = document.querySelector('.alert-box');
  if (prev) prev.remove();


  let alert = document.createElement('div');
  alert.className = 'alert-box';
  if (type === 'success') alert.classList.add('success');
  if (type === 'error') alert.classList.add('error');
  if (type === 'warning') alert.classList.add('warning');

  // Crear iconos para cada tipo
  let iconSpan = document.createElement('span');
  iconSpan.className = 'alert-icon';
  if (type === 'success') {
    iconSpan.innerHTML = '&#10004;'; // check mark
  } else if (type === 'error') {
    iconSpan.innerHTML = '&#10006;'; // cross mark
  } else if (type === 'warning') {
    iconSpan.innerHTML = '&#9888;'; // warning sign
  }
  alert.appendChild(iconSpan);

  // Crear botón de cierre
  let closeBtn = document.createElement('button');
  closeBtn.className = 'close-btn';
  closeBtn.innerHTML = '&times;';
  closeBtn.onclick = () => {
    alert.classList.add('hide');
    setTimeout(() => alert.remove(), 400);
  };
  alert.appendChild(closeBtn);

  // Añadir mensaje
  let messageSpan = document.createElement('span');
  messageSpan.className = 'alert-message';
  messageSpan.innerHTML = message;
  alert.appendChild(messageSpan);

  // Insertar alerta centrada y encima del container
  const container = document.querySelector(insertBeforeSelector);
  if (container) {
    // Crear un contenedor para posicionar la alerta encima y centrada
    let wrapper = document.createElement('div');
    wrapper.style.position = 'absolute';
    wrapper.style.top = '0';
    wrapper.style.left = '50%';
    wrapper.style.transform = 'translateX(-50%)';
    wrapper.style.width = '100%';
    wrapper.style.display = 'flex';
    wrapper.style.justifyContent = 'center';
    wrapper.style.zIndex = '9999';
    wrapper.style.marginBottom = '1rem';
    wrapper.appendChild(alert);
    container.style.position = 'relative'; // Asegurar que el container sea relativo para posicionar el wrapper
    container.prepend(wrapper);
  } else {
    document.body.prepend(alert);
  }

  // Oculta la alerta después de 4 segundos
  setTimeout(() => {
    alert.classList.add('hide');
    setTimeout(() => alert.remove(), 400);
  }, 4000);
}
