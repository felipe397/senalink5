document.addEventListener("DOMContentLoaded", () => {
    fetch("../../../controllers/UsuarioController.php?action=listarPrograma")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(programas => {
            const container = document.querySelector(".cardh__container");

            if (!container) {
                console.error("No se encontró el contenedor con clase .cardh__container");
                return;
            }

            container.innerHTML = ""; // Limpiar contenido inicial

            programas.forEach(programa => {
                const card = document.createElement("article");
                card.classList.add("cardh");

                card.innerHTML = `
                    <a href="Programa de formacion.html?id=${encodeURIComponent(programa.id)}">
                        <div class="card-text">
                            <h2 class="card-title">${programa.nombre_programa}</h2>
                            <p class="card-subtitle">${programa.ficha}</p>
                        </div>
                    </a>
                `;

                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Error al cargar los programas de formación:", error);
        });
});

document.addEventListener("DOMContentLoaded", function () {
    const id = new URLSearchParams(window.location.search).get('id');

    if (id) {
        fetch(`../../../controllers/UsuarioController.php?action=DetallePrograma&id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                } else {
                    document.querySelector('p:nth-of-type(1) span').textContent = data.codigo || "";
                    document.querySelector('p:nth-of-type(2) span').textContent = data.ficha || "";
                    document.querySelector('p:nth-of-type(3) span').textContent = data.nivel_formacion || "";
                    document.querySelector('p:nth-of-type(4) span').textContent = data.nombre_programa || "";
                    document.querySelector('p:nth-of-type(5) span').textContent = data.descripcion || "";
                    document.querySelector('p:nth-of-type(6) span').textContent = data.habilidades_requeridas || "";
                    document.querySelector('p:nth-of-type(7) span').textContent = data.fecha_finalizacion || "";
                    document.querySelector('p:nth-of-type(8) span').textContent = data.estado || "";
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del programa:', error);
            });
    }
});
