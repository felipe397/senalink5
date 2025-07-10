document.addEventListener("DOMContentLoaded", function () {
  let programaId = new URLSearchParams(window.location.search).get('id');

  if (!programaId) {
    programaId = sessionStorage.getItem('programaId');
    if (programaId) {
      const newUrl = new URL(window.location.href);
      newUrl.searchParams.set('id', programaId);
      window.history.replaceState({}, '', newUrl);
    }
  }

  if (programaId) {
    fetch(`http://localhost/senalink5/senalink5/senalink/controllers/ProgramaController.php?action=DetallePrograma&id=${programaId}`)
      .then(response => response.json())
      .then(data => {
        console.log('Datos recibidos del backend:', data);
        if (data.error) {
          console.error(data.error);
        } else {
          // Mostrar datos en la vista
          document.getElementById('codigo').textContent = data.codigo || '';
          document.getElementById('ficha').textContent = data.ficha || '';
          document.getElementById('nivel_formacion').textContent = data.nivel_formacion || '';
          document.getElementById('nombre_programa').textContent = data.nombre_programa || '';
          document.getElementById('duracion_programa').textContent = data.duracion_programa || '';
          document.getElementById('nombre_ocupacion').textContent = data.nombre_ocupacion || '';
          document.getElementById('sector_programa').textContent = data.sector_programa || '';
          document.getElementById('etapa_ficha').textContent = data.etapa_ficha || '';
          document.getElementById('sector_economico').textContent = data.sector_economico || '';
          document.getElementById('fecha_finalizacion').textContent = data.fecha_finalizacion || '';
          document.getElementById('estado').textContent = data.estado || '';
        }
      })
      .catch(error => {
        console.error('Error al cargar los detalles del programa:', error);
      });
  }

  // Descargar PDF
  document.querySelector('.btn__report').addEventListener('click', () => {
    const form = document.getElementById('formReporte');

    form.elements['codigo'].value = document.getElementById('codigo').textContent.trim();
    form.elements['ficha'].value = document.getElementById('ficha').textContent.trim();
    form.elements['nivel_formacion'].value = document.getElementById('nivel_formacion').textContent.trim();
    form.elements['nombre_programa'].value = document.getElementById('nombre_programa').textContent.trim();
    form.elements['duracion_programa'].value = document.getElementById('duracion_programa').textContent.trim();
    form.elements['nombre_ocupacion'].value = document.getElementById('nombre_ocupacion').textContent.trim();
    form.elements['sector_programa'].value = document.getElementById('sector_programa').textContent.trim();
    form.elements['etapa_ficha'].value = document.getElementById('etapa_ficha').textContent.trim();
    form.elements['sector_economico'].value = document.getElementById('sector_economico').textContent.trim();
    form.elements['fecha_finalizacion'].value = document.getElementById('fecha_finalizacion').textContent.trim();
    form.elements['estado'].value = document.getElementById('estado').textContent.trim();

    form.submit();
  });
});
