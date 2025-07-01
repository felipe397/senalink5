document.addEventListener("DOMContentLoaded", function () {

    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    const filtroActivo = document.getElementById('filtro-activo');
    const filtroDesactivado = document.getElementById('filtro-desactivado');
    let empresas = [];
    // Leer el estado desde la URL, si existe
    const params = new URLSearchParams(window.location.search);
    let estadoActual = 'Activo';
    if (params.has('estado')) {
        const estadoParam = params.get('estado');
        if (estadoParam.toLowerCase() === 'desactivado') {
            estadoActual = 'Desactivado';
        } else {
            estadoActual = 'Activo';
        }
    }

    // Detectar si estamos en Gestion_Empresa.html (listado)

    if (container && inputBusqueda) {
        // Función para cargar empresas según estado
        function cargarEmpresasPorEstado(estado) {
            estadoActual = estado;
            let url = "";
            if (estado === "Activo") {
                url = "../../../controllers/EmpresaController.php?action=listarEmpresasActivas";
            } else if (estado === "Desactivado") {
                url = "../../../controllers/EmpresaController.php?action=listarEmpresasInhabilitadas";
            }
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    empresas = data;
                    renderEmpresas(empresas);
                })
                .catch(error => {
                    console.error("Error al cargar las empresas:", error);
                });
        }

        // Cargar empresas según el estado de la URL al inicio
        cargarEmpresasPorEstado(estadoActual);

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

        // Filtros de estado
        if (filtroActivo && filtroDesactivado) {
            filtroActivo.addEventListener('click', function () {
                cargarEmpresasPorEstado('Activo');
            });
            filtroDesactivado.addEventListener('click', function () {
                cargarEmpresasPorEstado('Desactivado');
            });
        }

        // Renderizar empresas y manejar click en card
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
                    <div class="card-text">
                        <h2 class="card-title">${empresa.razon_social}</h2>
                        <p class="card-subtitle">${empresa.nit}</p>
                    </div>
                `;
                card.style.cursor = 'pointer';
                card.addEventListener('click', function () {
                    if (estadoActual === 'Activo') {
                        window.location.href = `Empresa.html?id=${empresa.id}`;
                    } else {
                        window.location.href = `EmpresaInhabilitado.html?id=${empresa.id}`;
                    }
                });
                container.appendChild(card);
            });
        }
        // Exponer para recarga externa
        window.recargarEmpresas = () => cargarEmpresasPorEstado(estadoActual);
    }

    // Detectar si estamos en Empresa.html (detalle)
    const empresaId = new URLSearchParams(window.location.search).get('id');
    if (empresaId && window.location.pathname.includes('Empresa.html')) {
        fetch(`http://localhost/senalink5/senalink5/senalink/controllers/UsuarioController.php?action=detalleEmpresa&id=${empresaId}`)
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
// Confirmación genérica para inhabilitar/habilitar
function confirmInhabilitar(callback) {
    const modal = document.getElementById("custom-confirm");
    modal.classList.add("show");
    document.getElementById("confirm-yes").onclick = function () {
        modal.classList.remove("show");
        if (typeof callback === "function") {
            callback();
        }
    };
    document.getElementById("confirm-no").onclick = function () {
        modal.classList.remove("show");
    };
}

// Manejo de inhabilitar/habilitar en Empresa.html y EmpresaInhabilitado.html
document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const empresaId = params.get('id');
    // Inhabilitar (Empresa.html)
    const btnInhabilitar = document.getElementById('btn-inhabilitar');
    if (btnInhabilitar && empresaId) {
        btnInhabilitar.dataset.empresaId = empresaId;
        btnInhabilitar.addEventListener('click', function () {
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
                    // Volver al listado y recargar empresas activas/desactivadas
                    if (window.opener && window.opener.recargarEmpresas) {
                        window.opener.recargarEmpresas();
                    }
                    window.location.href = '/senalink5/senalink/html/Super_Admin/Empresa/Gestion_Empresa.html';

                })
                .catch(error => console.error('Error:', error));
            });
        });
    }
    // Habilitar (EmpresaInhabilitado.html)
    const btnHabilitar = document.getElementById('btn-habilitar');
        if (btnHabilitar && empresaId) {
            btnHabilitar.dataset.empresaId = empresaId;
            btnHabilitar.addEventListener('click', function () {
                console.log("btn-habilitar clicked");
                // En EmpresaInhabilitado.html no hay modal, hacer fetch directo
                if (window.location.pathname.includes('EmpresaInhabilitado.html')) {
                    const formData = new URLSearchParams();
                    formData.append('accion', 'actualizarEstado');
                    formData.append('id', empresaId);
                    formData.append('estado', 'Activo');
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
                        if (window.opener && window.opener.recargarEmpresas) {
                            window.opener.recargarEmpresas();
                        }
                        window.location.href = '/senalink5/senalink/html/Super_Admin/Empresa/Gestion_Empresa.html';
                    })
                    .catch(error => console.error('Error:', error));
                } else {
                    confirmInhabilitar(() => {
                        console.log("confirmInhabilitar callback");
                        const formData = new URLSearchParams();
                        formData.append('accion', 'actualizarEstado');
                        formData.append('id', empresaId);
                        formData.append('estado', 'Activo');
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
                    if (window.opener && window.opener.recargarEmpresas) {
                        window.opener.recargarEmpresas();
                    }
                    window.location.href = '/senalink5/senalink5/senalink/html/Super_Admin/Empresa/Gestion_Empresa.html';
                        })
                        .catch(error => console.error('Error:', error));
                    });
                }
            });
        }
});



