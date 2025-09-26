// Mostrar/ocultar el menú de filtro de estado
const estadoDropdownBtn = document.getElementById('estadoDropdownBtn');
const dropdown = document.querySelector('.dropdown');
if (estadoDropdownBtn && dropdown) {
  estadoDropdownBtn.addEventListener('click', function () {
    dropdown.classList.toggle('show');
  });
  // Cerrar el menú si se hace click fuera
  document.addEventListener('click', function (e) {
    if (!dropdown.contains(e.target) && e.target !== estadoDropdownBtn) {
      dropdown.classList.remove('show');
    }
  });
}
document.addEventListener('DOMContentLoaded', function () {
  const container = document.querySelector('.cardh__container');
  const inputBusqueda = document.querySelector('.search-container input');
  const filtroActivo = document.getElementById('filtro-activo');
  const filtroDesactivado = document.getElementById('filtro-desactivado');
  let funcionarios = [];
  let estadoActual = 'Activo';

  function cargarFuncionariosPorEstado(estado) {
    estadoActual = estado;
    fetch(
      `../../../controllers/UsuarioController.php?action=listarAdminSENA&estado=${estado}&_=${Date.now()}`
    ) // cache busting
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          funcionarios = data.data;
          renderFuncionarios(funcionarios);
        } else {
          container.innerHTML = '<p>No se encontraron usuarios AdminSENA.</p>';
        }
      })
      .catch((error) => {
        console.error('Error al cargar usuarios AdminSENA:', error);
        container.innerHTML = '<p>Error al cargar usuarios.</p>';
      });
  }

  function renderFuncionarios(funcionarios) {
    container.innerHTML = '';
    if (funcionarios.length === 0) {
      container.innerHTML = '<p>No se encontraron usuarios AdminSENA.</p>';
      return;
    }
    funcionarios.forEach((usuario) => {
      const card = document.createElement('div');
      card.className = 'cardh';
      let nombreCompleto = usuario.primer_nombre;
      if (usuario.segundo_nombre && usuario.segundo_nombre.trim() !== '') {
        nombreCompleto += ' ' + usuario.segundo_nombre;
      }
      nombreCompleto +=
        ' ' + usuario.primer_apellido + ' ' + usuario.segundo_apellido;
      card.innerHTML = `
                <h3>${nombreCompleto}</h3>
                <p>Número de documento: ${usuario.numero_documento}</p>
                <p>Estado: <span class="estado-label">${usuario.estado}</span></p>
            `;
      // Botón editar eliminado
      // card.querySelector('.edit-btn').addEventListener('click', (e) => {
      //     e.stopPropagation();
      //     window.location.href = `FuncionarioEdit.html?id=${usuario.id}`;
      // });
      // Click en la tarjeta (opcional: ver detalle)
      card.addEventListener('click', () => {
        window.location.href = `Funcionario.html?id=${usuario.id}`;
      });
      container.appendChild(card);
    });
  }

  cargarFuncionariosPorEstado(estadoActual);

  inputBusqueda.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    const filtrados =
      q.length >= 1
        ? funcionarios.filter((u) => {
            const nombreCompleto =
              (u.primer_nombre || '') +
              ' ' +
              (u.segundo_nombre || '') +
              ' ' +
              (u.primer_apellido || '') +
              ' ' +
              (u.segundo_apellido || '');
            const doc =
              u.numero_documento !== undefined && u.numero_documento !== null
                ? String(u.numero_documento).toLowerCase()
                : '';
            return nombreCompleto.toLowerCase().includes(q) || doc.includes(q);
          })
        : funcionarios;
    renderFuncionarios(filtrados);
  });

  if (filtroActivo && filtroDesactivado) {
    filtroActivo.addEventListener('click', () => {
      cargarFuncionariosPorEstado('Activo');
      setTimeout(() => {
        document.querySelector('.dropdown').classList.remove('show');
      }, 100);
    });
    filtroDesactivado.addEventListener('click', () => {
      cargarFuncionariosPorEstado('Desactivado');
      setTimeout(() => {
        document.querySelector('.dropdown').classList.remove('show');
      }, 100);
    });
  }

  window.recargarFuncionarios = () => cargarFuncionariosPorEstado(estadoActual);
});
