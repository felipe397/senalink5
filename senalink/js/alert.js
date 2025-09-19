// Función reutilizable para mostrar alertas personalizadas en cualquier HTML
// Uso: showAlert('Mensaje', 'success'|'error'|'warning', selectorDondeInsertar)

function showAlert(message, type = 'error', insertBeforeSelector) {
  // Elimina alertas previas
  const prev = document.querySelector('.alert-box');
  if (prev) prev.remove();

  let alert = document.createElement('div');
  alert.className = 'alert-box';
  // Estilos generales
  alert.style.fontSize = '1.25rem';
  alert.style.padding = '2rem 2.5rem';
  alert.style.borderRadius = '1.2rem';
  alert.style.boxShadow = '0 6px 32px 0 rgba(0,0,0,0.18)';
  alert.style.maxWidth = '600px';
  alert.style.minWidth = '340px';
  alert.style.width = 'fit-content';
  alert.style.letterSpacing = '0.01em';
  alert.style.lineHeight = '1.5';
  alert.style.fontWeight = '500';
  alert.style.textAlign = 'center';
  alert.style.marginTop = '1.5rem';
  alert.style.marginBottom = '1.5rem';
  alert.style.border = '2.5px solid #222';
  alert.style.background = '#fff';
  alert.style.color = '#222';
  if (type === 'success') {
    alert.style.background = '#e6fbe6'; // verde claro
    alert.style.borderColor = '#1b5e20'; // verde oscuro
    alert.style.color = '#1b5e20';
  }
  if (type === 'error') {
    alert.style.background = '#ffeaea'; // rojo claro
    alert.style.borderColor = '#b71c1c'; // rojo oscuro
    alert.style.color = '#b71c1c';
  }
  if (type === 'warning') {
    alert.style.background = '#fffbe6'; // amarillo claro
    alert.style.borderColor = '#bfa100'; // amarillo oscuro
    alert.style.color = '#bfa100';
  }

  // Crear iconos para cada tipo
  let iconSpan = document.createElement('span');
  iconSpan.className = 'alert-icon';
  if (type === 'success') {
    iconSpan.innerHTML = '&#10004;'; // check mark
  } else if (type === 'error') {
    // Usar icono diferente para error para no confundir con botón de cierre
    iconSpan.innerHTML = '&#9940;'; // heavy multiplication x
  } else if (type === 'warning') {
    iconSpan.innerHTML = '&#9888;'; // warning sign
  }
  alert.appendChild(iconSpan);

  // Crear botón de cierre
  let closeBtn = document.createElement('button');
  closeBtn.className = 'close-btn';
  closeBtn.innerHTML = '&times;';
  // Añadir estilos inline para asegurar tamaño y estilo correcto
  closeBtn.style.width = '1.2rem';
  closeBtn.style.height = '1.2rem';
  closeBtn.style.display = 'flex';
  closeBtn.style.alignItems = 'center';
  closeBtn.style.justifyContent = 'center';
  closeBtn.style.borderRadius = '50%';
  closeBtn.style.padding = '0';
  closeBtn.style.margin = '0';
  closeBtn.style.background = 'transparent';
  closeBtn.style.border = 'none';
  closeBtn.style.cursor = 'pointer';
  closeBtn.style.fontWeight = 'bold';
  closeBtn.style.lineHeight = '1';
  closeBtn.style.color = 'inherit';
  closeBtn.onclick = () => {
    alert.classList.add('hide');
    setTimeout(() => alert.remove(), 400);
  };

  // Crear contenedor para mensaje y botón para alinearlos horizontalmente
  let contentWrapper = document.createElement('div');
  contentWrapper.style.display = 'flex';
  contentWrapper.style.alignItems = 'center';
  contentWrapper.style.justifyContent = 'space-between';
  contentWrapper.style.width = '100%';

  // Añadir mensaje
  let messageSpan = document.createElement('span');
  messageSpan.className = 'alert-message';
  messageSpan.innerHTML = message;

  contentWrapper.appendChild(messageSpan);
  contentWrapper.appendChild(closeBtn);
  alert.appendChild(contentWrapper);

  // Insertar alerta centrada y encima del container
  let container;
  if (insertBeforeSelector) {
    container = document.querySelector(insertBeforeSelector);
  } else {
    container = document.querySelector('.container__crud') || document.body;
  }
  if (container) {
    // Insertar la alerta en body con posición fija y top ajustado para que aparezca entre header y container
    alert.style.position = 'fixed';
    alert.style.top = '119px';  // Ajustar para que quede un poco más abajo, entre header y container
    alert.style.left = '50%';
    alert.style.transform = 'translateX(-50%)';
    alert.style.width = 'auto';
    alert.style.maxWidth = '440px';
    alert.style.marginBottom = '0';
    alert.style.zIndex = '9999';
    document.body.prepend(alert);
  } else {
    document.body.prepend(alert);
  }

  // Oculta la alerta después de 4 segundos
  setTimeout(() => {
    alert.classList.add('hide');
    setTimeout(() => alert.remove(), 1000);
  }, 10000);
}
