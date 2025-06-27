document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    let empresas = [];

    // Detectar si estamos en Gestion_Empresa.html (listado)
    if (container && inputBusqueda) {
        console.log("viewempresas.js: contenedor y input encontrados, iniciando carga de empresas.");

        // Cargar empresas al inicio
        fetch("../../../controllers/UsuarioController.php?action=listarEmpresasActivas")
            .then(response => response.json())
            .then(data => {
                empresas = data;
                renderEmpresas(empresas);
            })
            .catch(error => {
                console.error("Error al cargar las empresas:", error);
            });
        // Buscar en tiempo real
        inputBusqueda.addEventListener("input", function () {
            const q = this.value.toLowerCase();
            if (q.length >= 1) {
                const filtradas = empresas.filter(e =>
                    e.razon_social.toLowerCase().startsWith(q) || e.nit.toLowerCase().startsWith(q)
                );
                renderEmpresas(filtradas);
            } else {
                renderEmpresas(empresas);
            }
        });

        function renderEmpresas(empresas) {
            container.innerHTML = "";

            if (empresas.length === 0) {
                container.innerHTML = "<p>No se encontraron empresas.</p>";
                return;
            }

            empresas.forEach(empresa => {
                const card = document.createElement("article");
                card.classList.add("cardh");

                card.innerHTML = `
                    <a href="Empresa.html?id=${empresa.id}">
                        <div class="card-text">
                            <h2 class="card-title">${empresa.razon_social}</h2>
                            <p class="card-subtitle">${empresa.nit}</p>
                        </div>
                    </a>
                `;

                container.appendChild(card);
            });
        }
    }

    // Detectar si estamos en Empresa.html (detalle)
    const empresaId = new URLSearchParams(window.location.search).get('id');
    if (empresaId) {
        fetch(`http://localhost/senalink5/senalink/controllers/UsuarioController.php?action=detalleEmpresa&id=${empresaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    document.getElementById('nit').textContent = data.nit;
                    document.getElementById('representante').textContent = data.representante_legal;
                    document.getElementById('razon_social').textContent = data.razon_social;
                    document.getElementById('telefono').textContent = data.telefono;
                    document.getElementById('correo').textContent = data.correo.trim();
                    document.getElementById('ubicacion').textContent = data.direccion;
                    document.getElementById('tipo_empresa').textContent = data.tipo_empresa;
                    document.getElementById('estado').textContent = data.estado;

                    const actualizarLink = document.querySelector('a.buttons__crud[href="EmpresaEdit.html"]');
                    if (actualizarLink) {
                        actualizarLink.href = `EmpresaEdit.html?id=${empresaId}`;
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles de la empresa:', error);
            });
    }
});
function confirmInhabilitar(callback) {
    const modal = document.getElementById("custom-confirm");
    modal.classList.add("show");

    document.getElementById("confirm-yes").onclick = function () {
        modal.classList.remove("show");
        if (typeof callback === "function") {
            callback(); // Aquí se ejecuta el fetch solo si confirman
        }
    };

    document.getElementById("confirm-no").onclick = function () {
        modal.classList.remove("show");
    };
}

// Este se ejecuta solo al hacer clic en el botón de "Inhabilitar"
document.getElementById('btn-inhabilitar').addEventListener('click', function () {
    const empresaId = this.dataset.empresaId;
    const estadoActual = document.getElementById('estado').textContent.trim();
    const nuevoEstado = estadoActual === 'Activo' ? 'Desactivado' : 'Activo';

    confirmInhabilitar(() => {
        const formData = new URLSearchParams();
        formData.append('accion', 'actualizarEstado');
        formData.append('id', empresaId);
        formData.append('estado', nuevoEstado);

        fetch('../../../controllers/UsuarioController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            window.location.href='../html/Super_Admin/Empresa/Gestion_empresas_inhabilitadas.html' // refresca la página para ver el nuevo estado
        })
        .catch(error => console.error('Error:', error));
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const empresaId = params.get('id');

    if (empresaId) {
        const btn = document.getElementById('btn-inhabilitar');
        btn.dataset.empresaId = empresaId;
    }
});



