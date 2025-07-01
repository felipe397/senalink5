document.addEventListener("DOMContentLoaded", function () {
  // Obtener el ID desde la URL
  let empresaId = new URLSearchParams(window.location.search).get('id');

  // Si no hay ID en la URL, intentar obtenerlo desde sessionStorage
  if (!empresaId) {
    empresaId = sessionStorage.getItem('empresaId');
    if (empresaId) {
      // Actualizar la URL para incluir el ID sin recargar la página
      const newUrl = new URL(window.location.href);
      newUrl.searchParams.set('id', empresaId);
      window.history.replaceState({}, '', newUrl);
    }
  }

  if (empresaId) {
    // Hacer una solicitud GET al backend para obtener los detalles de la empresa
    fetch(`http://localhost/senalink5/senalink/controllers/EmpresaController.php?action=detalleEmpresa&id=${empresaId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Datos recibidos del backend:', data);
        if (data.error) {
          console.error(data.error);
        } else {
          // Actualizar los elementos HTML con los datos de la empresa
          document.getElementById('nit').textContent = data.nit || '';
          document.getElementById('representante').textContent = data.representante_legal || '';
          document.getElementById('razon_social').textContent = data.razon_social || '';
          document.getElementById('telefono').textContent = data.telefono || '';
          document.getElementById('correo').textContent = data.correo || '';
          document.getElementById('ubicacion').textContent = data.direccion || '';
          document.getElementById('tipo_empresa').textContent = data.tipo_empresa || '';
          document.getElementById('estado').textContent = data.estado || '';
        }
      })
      .catch(error => {
        console.error('Error al cargar los detalles de la empresa:', error);
      });
  }

  // Evento para el botón descargar que envía el formulario oculto
  document.querySelector('.btn__report').addEventListener('click', () => {
    const form = document.getElementById('formReporte');

    // Copiar datos visibles a inputs ocultos
    form.elements['nit'].value = document.getElementById('nit').textContent.trim();
    form.elements['representante'].value = document.getElementById('representante').textContent.trim();
    form.elements['razon_social'].value = document.getElementById('razon_social').textContent.trim();
    form.elements['telefono'].value = document.getElementById('telefono').textContent.trim();
    form.elements['correo'].value = document.getElementById('correo').textContent.trim();
    form.elements['ubicacion'].value = document.getElementById('ubicacion').textContent.trim();
    form.elements['tipo_empresa'].value = document.getElementById('tipo_empresa').textContent.trim();
    form.elements['estado'].value = document.getElementById('estado').textContent.trim();

    // Enviar formulario para generar PDF
    form.submit();
  });
});
