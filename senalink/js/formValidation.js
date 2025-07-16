// Validación personalizada para formularios con mensajes tipo tooltip/burbuja

document.addEventListener('DOMContentLoaded', () => {
  // Función para crear tooltip de error
  function createErrorTooltip(message) {
    const tooltip = document.createElement('div');
    tooltip.className = 'error-tooltip';
    tooltip.textContent = message;

    const icon = document.createElement('span');
    icon.className = 'error-icon';
    icon.innerHTML = '&#9888;'; // icono de advertencia
    tooltip.prepend(icon);

    return tooltip;
  }

  // Función para mostrar tooltip junto al input
  function showError(input, message) {
    removeError(input);
    const tooltip = createErrorTooltip(message);
    input.classList.add('input-error');
    input.parentNode.style.position = 'relative';
    tooltip.style.position = 'absolute';
    tooltip.style.top = '100%';
    tooltip.style.left = '0';
    tooltip.style.marginTop = '4px';
    input.parentNode.appendChild(tooltip);
  }

  // Función para eliminar tooltip y estilos de error
  function removeError(input) {
    input.classList.remove('input-error');
    const parent = input.parentNode;
    const existingTooltip = parent.querySelector('.error-tooltip');
    if (existingTooltip) {
      existingTooltip.remove();
    }
  }

  // Validar un input individual, ignorando readonly y disabled
  function validateInput(input) {
    if (input.hasAttribute('readonly') || input.hasAttribute('disabled')) {
      removeError(input);
      return true;
    }
    // Validar solo si el campo es visible y tiene valor
    if (input.offsetParent === null) {
      removeError(input);
      return true;
    }
    if (!input.checkValidity()) {
      showError(input, input.validationMessage);
      return false;
    } else {
      removeError(input);
      return true;
    }
  }

  // Validar formulario completo
  function validateForm(form) {
    let valid = true;
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
      if (!validateInput(input)) {
        valid = false;
      }
    });
    return valid;
  }

  // Aplicar validación a todos los formularios con clase 'validated-form'
  const forms = document.querySelectorAll('form.validated-form');
  forms.forEach(form => {
  // Validar inputs en tiempo real, ignorando readonly y disabled
  form.querySelectorAll('input:not([readonly]):not([disabled]), select:not([readonly]):not([disabled]), textarea:not([readonly]):not([disabled])').forEach(input => {
    input.addEventListener('input', () => validateInput(input));
    input.addEventListener('blur', () => validateInput(input));
  });

  // Validar al enviar formulario, ignorando readonly y disabled
  form.addEventListener('submit', e => {
    if (!validateForm(form)) {
      e.preventDefault();
    }
  });
  });
});
