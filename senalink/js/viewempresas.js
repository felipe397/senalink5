document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    const filtroActivo = document.getElementById('filtro-activo');
    const filtroDesactivado = document.getElementById('filtro-desactivado');
    let empresas = [];

    const params = new URLSearchParams(window.location.search);
    let estadoActual = 'Activo';
    if (params.has('estado')) {
        const estadoParam = params.get('estado');
        estadoActual = estadoParam.toLowerCase() === 'desactivado' ? 'Desactivado' : 'Activo';
    }

    if (container && inputBusqueda) {
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

        cargarEmpresasPorEstado(estadoActual);

        inputBusqueda.addEventListener("input", function () {
            const q = this.value.toLowerCase();
            const filtradas = q.length >= 1
                ? empresas.filter(e => e.razon_social.toLowerCase().startsWith(q) || e.nit.toLowerCase().startsWith(q))
                : empresas;
            renderEmpresas(filtradas);
        });

        if (filtroActivo && filtroDesactivado) {
            filtroActivo.addEventListener('click', () => cargarEmpresasPorEstado('Activo'));
            filtroDesactivado.addEventListener('click', () => cargarEmpresasPorEstado('Desactivado'));
        }

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
                card.addEventListener('click', () => {
                    window.location.href = `Empresa.html?id=${empresa.id}`;
                });
                container.appendChild(card);
            });
        }

        window.recargarEmpresas = () => cargarEmpresasPorEstado(estadoActual);
    }

    // DETALLE DE EMPRESA
    const empresaId = params.get('id');
    if (empresaId) {
        fetch(`http://localhost/senalink5/senalink5/senalink/controllers/EmpresaController.php?action=detalleEmpresa&id=${empresaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    document.getElementById('nit').textContent = data.nit;
                    document.getElementById('representante').textContent = data.representante_legal;
                    document.getElementById('razon_social').textContent = data.razon_social;
                    document.getElementById('telefono').textContent = data.telefono;
                    document.getElementById('correo').textContent = (data.correo || '').trim();
                    document.getElementById('ubicacion').textContent = data.direccion;
                    document.getElementById('tipo_empresa').textContent = data.tipo_empresa;
                    document.getElementById('estado').textContent = data.estado;

                    const actualizarLink = document.querySelector('a.buttons__crud[href="EmpresaEdit.html"]');
                    const btnReporte = document.getElementById('btnReporte');
                    if (actualizarLink) {
                        actualizarLink.href = `EmpresaEdit.html?id=${empresaId}`;
                    }

                    // Ocultar/mostrar botones según estado
                    if (data.estado && data.estado.trim().toLowerCase() === 'desactivado') {
                        if (actualizarLink) actualizarLink.style.display = 'none';
                        if (btnReporte) btnReporte.style.display = 'none';
                    } else {
                        if (actualizarLink) actualizarLink.style.display = '';
                        if (btnReporte) btnReporte.style.display = '';
                    }

                    const btnInhabilitar = document.getElementById('btn-inhabilitar');
                    const btnHabilitar = document.getElementById('btn-habilitar');
                    if ((data.estado || '').toLowerCase() === 'activo') {
                        if (btnInhabilitar) btnInhabilitar.style.display = 'inline-block';
                        if (btnHabilitar) btnHabilitar.style.display = 'none';
                    } else {
                        if (btnInhabilitar) btnInhabilitar.style.display = 'none';
                        if (btnHabilitar) btnHabilitar.style.display = 'inline-block';
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles de la empresa:', error);
            });
    }
});

// MODAL de confirmación
function confirmInhabilitar(callback) {
    const modal = document.getElementById("custom-confirm");
    modal.classList.add("show");
    document.getElementById("confirm-yes").onclick = function () {
        modal.classList.remove("show");
        if (typeof callback === "function") callback();
    };
    document.getElementById("confirm-no").onclick = function () {
        modal.classList.remove("show");
    };
}

// BOTONES de inhabilitar/habilitar
document.addEventListener('DOMContentLoaded', () => {
    const empresaId = new URLSearchParams(window.location.search).get('id');
    const btnInhabilitar = document.getElementById('btn-inhabilitar');
    const btnHabilitar = document.getElementById('btn-habilitar');

    if (btnInhabilitar && empresaId) {
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
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: formData
                })
                    .then(response => response.text())
                    .then(data => {
                        // Si la respuesta es JSON, mostrar solo el mensaje
                        try {
                            const res = JSON.parse(data);
                            if (message) {
                                alert(res.message);
                            } else {
                                alert(data);
                            }
                        } catch (e) {
                            alert(data);
                        }
                        if (window.opener && window.opener.recargarEmpresas) {
                            window.opener.recargarEmpresas();
                        }
                        window.location.href = 'http://localhost/senalink5/senalink5/senalink/html/Super_Admin/Empresa/Gestion_Empresa.html';
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    }

    if (btnHabilitar && empresaId) {
        btnHabilitar.addEventListener('click', function () {
            const formData = new URLSearchParams();
            formData.append('accion', 'actualizarEstado');
            formData.append('id', empresaId);
            formData.append('estado', 'Activo');
            fetch('../../../controllers/UsuarioController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    // Si la respuesta es JSON, mostrar solo el mensaje
                    try {
                        const res = JSON.parse(data);
                        if (res.message) {
                            alert(message);
                        } else {
                            alert(data);
                        }
                    } catch (e) {
                        alert(data);
                    }
                    if (window.opener && window.opener.recargarEmpresas) {
                        window.opener.recargarEmpresas();
                    }
                    // Redirigir según el tipo de usuario (empresa o AdminSENA)
                    const params = new URLSearchParams(window.location.search);
                    if (params.get('tipo') === 'funcionario' || params.get('rol') === 'AdminSENA') {
                        window.location.href = '/senalink5/senalink5/senalink/html/Super_Admin/Funcionarios/Gestion_Funcionario.html';
                    } else {
                        window.location.href = '/senalink5/senalink5/senalink/html/Super_Admin/Empresa/Gestion_Empresa.html';
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    }
});
    // Mostrar advertencia en login si la empresa está inhabilitada
    function mostrarAdvertenciaEmpresaInhabilitada() {
        const params = new URLSearchParams(window.location.search);
        if (params.get('empresa_inhabilitada') === '1') {
            const advertDiv = document.getElementById('empresa-inhabilitada-advertencia');
            const mensaje = '⚠️ Su empresa está inhabilitada. Por favor contacte al administrador.';
            if (advertDiv) {
                advertDiv.textContent = mensaje;
                advertDiv.style.display = 'block';
            } else {
                // Fallback: crear el div si no existe (por si lo llaman en otra vista)
                let advert = document.createElement('div');
                advert.textContent = mensaje;
                advert.style.color = '#c0392b';
                advert.style.fontWeight = 'bold';
                advert.style.textAlign = 'center';
                advert.style.margin = '1.5rem 0 0 0';
                advert.style.fontSize = '1.5rem';
                const loginContainer = document.querySelector('.container__forms--login');
                if (loginContainer) {
                    loginContainer.prepend(advert);
                }
            }
            alert(mensaje);
        }
    }
    document.addEventListener('DOMContentLoaded', mostrarAdvertenciaEmpresaInhabilitada);
