document.addEventListener("DOMContentLoaded", function () {
  // Obtener el ID desde la URL
  let programaId = new URLSearchParams(window.location.search).get('id');

  // Si no hay ID en la URL, intentar obtenerlo desde sessionStorage
  if (!programaId) {
    programaId = sessionStorage.getItem('empresaId');
    if (programaId) {
      // Actualizar la URL para incluir el ID sin recargar la página
      const newUrl = new URL(window.location.href);
      newUrl.searchParams.set('id', programaId);
      window.history.replaceState({}, '', newUrl);
    }
  }

  if (programaId) {
    // Hacer una solicitud GET al backend para obtener los detalles de la empresa
    fetch(`http://localhost/senalink5/senalink5/senalink/controllers/ProgramaController.php?action=detallePrograma&id=${programaId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Datos recibidos del backend:', data);
        if (data.error) {
          console.error(data.error);
        } else {
          // Actualizar los elementos HTML con los datos de la empresa
        document.getElementById('codigo').textContent = data.nit || '';
        document.getElementById('ficha').textContent = data.representante_legal || '';
        document.getElementById('nivel_formacion').textContent = data.razon_social || '';
        document.getElementById('nombre_programa').textContent = data.telefono || '';
        document.getElementById('descripcion').textContent = data.correo || '';
        document.getElementById('ubicacion').textContent = data.direccion || '';
        document.getElementById('habilidades_requeridas').textContent = data.tipo_empresa || '';
        document.getElementById('fecha_finalizacion').textContent = data.estado || '';
        document.getElementById('estado').textContent = data.estado || '';

        }
      })
      .catch(error => {
        console.error('Error al cargar los detalles del programa:', error);
      });
  }

  // Evento para el botón descargar que envía el formulario oculto
  document.querySelector('.btn__report').addEventListener('click', () => {
    const form = document.getElementById('formReporte');

    // Copiar datos visibles a inputs ocultos
    form.elements['nit'].value = document.getElementById('nit').textContent.trim();
    form.elements['representante'].value = document.getElementById('representante').textContent.trim();
    form.elements['razon_social'].value = document.getElementById('razon-social').textContent.trim();
    form.elements['telefono'].value = document.getElementById('telefono').textContent.trim();
    form.elements['correo'].value = document.getElementById('correo').textContent.trim();
    form.elements['ubicacion'].value = document.getElementById('ubicacion').textContent.trim();
    form.elements['tipo_empresa'].value = document.getElementById('tipo-empresa').textContent.trim();
    form.elements['estado'].value = document.getElementById('estado').textContent.trim();

    // Enviar formulario para generar PDF
    form.submit();
  });
});
