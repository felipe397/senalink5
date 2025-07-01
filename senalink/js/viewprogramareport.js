document.addEventListener("DOMContentLoaded", function () {
  // Obtener el ID desde la URL
  let programaId = new URLSearchParams(window.location.search).get('id');

  // Si no hay ID en la URL, intentar obtenerlo desde sessionStorage
  if (!programaId) {
    programaId = sessionStorage.getItem('programaId');
    if (programaId) {
      // Actualizar la URL para incluir el ID sin recargar la página
      const newUrl = new URL(window.location.href);
      newUrl.searchParams.set('id', programaId);
      window.history.replaceState({}, '', newUrl);
    }
  }

  if (programaId) {
    // Hacer una solicitud GET al backend para obtener los detalles de la empresa
    fetch(`http://localhost/senalink5/senalink5/senalink/controllers/ProgramaController.php?action=DetallePrograma&id=${programaId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Datos recibidos del backend:', data);
        if (data.error) {
          console.error(data.error);
        } else {
          // Actualizar los elementos HTML con los datos de la empresa
        document.getElementById('codigo').textContent = data.codigo || '';
        document.getElementById('ficha').textContent = data.ficha || '';
        document.getElementById('nivel_formacion').textContent = data.nivel_formacion || '';
        document.getElementById('nombre_programa').textContent = data.nombre_programa || '';
        document.getElementById('descripcion').textContent = data.descripcion || '';
        document.getElementById('habilidades_requeridas').textContent = data.habilidades_requeridas || '';
        document.getElementById('fecha_finalizacion').textContent = data.fecha_finalizacion || '';
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
    form.elements['codigo'].value = document.getElementById('codigo').textContent.trim();
    form.elements['ficha'].value = document.getElementById('ficha').textContent.trim();
    form.elements['nivel_formacion'].value = document.getElementById('nivel_formacion').textContent.trim();
    form.elements['nombre_programa'].value = document.getElementById('nombre_programa').textContent.trim();
    form.elements['descripcion'].value = document.getElementById('descripcion').textContent.trim();
    form.elements['habilidades_requeridas'].value = document.getElementById('habilidades_requeridas').textContent.trim();
    form.elements['fecha_finalizacion'].value = document.getElementById('fecha_finalizacion').textContent.trim();
    form.elements['estado'].value = document.getElementById('estado').textContent.trim();

    // Enviar formulario para generar PDF
    form.submit();
  });
});
