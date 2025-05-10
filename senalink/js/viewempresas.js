document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/senalink5/senalink/controllers/UsuarioController.php?action=listarEmpresas")
        .then(response => response.json())
        .then(empresas => {
            const container = document.querySelector(".cardh__container");

            if (!container) {
                console.error("No se encontró el contenedor con clase .cardh__container");
                return;
            }

            container.innerHTML = ""; // Limpiar contenido inicial

            empresas.forEach(empresa => {
                const card = document.createElement("article");
                card.classList.add("cardh");

                card.innerHTML = `
                    <a href="Empresa.html?id=${empresa.id}">
                        <div class="card-text">
                            <h2 class="card-title">${empresa.nombre_empresa}</h2>
                            <p class="card-subtitle">${empresa.nit}</p>
                        </div>
                    </a>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error al cargar las empresas:", error);
        });
});
