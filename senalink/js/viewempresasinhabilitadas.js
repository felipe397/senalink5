document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    let empresas = [];

    if (container && inputBusqueda) {
        // Cargar empresas inhabilitadas al inicio
        fetch("../../../controllers/UsuarioController.php?action=listarEmpresasInhabilitadas")
            .then(response => response.json())
            .then(data => {
                empresas = data;
                renderEmpresas(empresas);
            })
            .catch(error => {
                console.error("Error al cargar las empresas inhabilitadas:", error);
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
                container.innerHTML = "<p>No se encontraron empresas inhabilitadas.</p>";
                return;
            }

            empresas.forEach(empresa => {
                const card = document.createElement("article");
                card.classList.add("cardh");

                card.innerHTML = `
                    <a href="EmpresaInhabilitado.html?id=${empresa.id}">
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
});
