document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    let empresas = [];

    if (!container) {
        console.error("No se encontró el contenedor o el input de búsqueda.");
        return;
    }

    // Cargar empresas al inicio
    fetch("../../../controllers/UsuarioController.php?action=listarEmpresas")
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
        const filtradas = empresas.filter(e =>
            e.razon_social.toLowerCase().includes(q) || e.nit.toLowerCase().includes(q)
        );
        renderEmpresas(filtradas);
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

    // DETALLE DE EMPRESA
    const empresaId = new URLSearchParams(window.location.search).get('id');
    if (empresaId) {
        fetch(`http://localhost/senalink5/senalink/controllers/UsuarioController.php?action=detalleEmpresa&id=${empresaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    document.querySelector('p:nth-of-type(1) span').textContent = data.nit;
                    document.querySelector('p:nth-of-type(2) span').textContent = data.representante_legal;
                    document.querySelector('p:nth-of-type(3) span').textContent = data.razon_social;
                    document.querySelector('p:nth-of-type(4) span').textContent = data.telefono;
                    document.querySelector('p:nth-of-type(5) span').textContent = data.correo;
                    document.querySelector('p:nth-of-type(6) span').textContent = data.direccion;
                    document.querySelector('p:nth-of-type(7) span').textContent = data.tipo_empresa;
                    document.querySelector('p:nth-of-type(8) span').textContent = data.estado;

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
