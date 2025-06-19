document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    let programas = [];

    if (!container) {
        console.error("No se encontró el contenedor con clase .cardh__container");
        return;
    }

    // Cargar todos los programas
    fetch("../../../controllers/UsuarioController.php?action=listarPrograma")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            programas = data;
            renderProgramas(programas);
        })
        .catch(error => {
            console.error("Error al cargar los programas de formación:", error);
            container.innerHTML = "<p>Error al cargar los programas de formación.</p>";
        });

    // Buscar en tiempo real
    if (inputBusqueda) {
        inputBusqueda.addEventListener("input", function () {
            const q = this.value.trim().toLowerCase();
            const filtrados = programas.filter(p =>
                p.nombre_programa?.toLowerCase().includes(q) ||
                p.ficha?.toLowerCase().includes(q) ||
                p.codigo?.toLowerCase().includes(q)
            );
            renderProgramas(filtrados);
        });
    }

    function renderProgramas(programas) {
        container.innerHTML = "";

        if (programas.length === 0) {
            container.innerHTML = "<p>No se encontraron programas de formación.</p>";
            return;
        }

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
                <a href="ProgramaEdit.html?id=${encodeURIComponent(programa.id)}" class="buttons__crud"></a>
            `;

            container.appendChild(card);
        });
    }

    // DETALLE DEL PROGRAMA
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
                    document.getElementById('codigo').textContent = data.codigo || "";
                    document.getElementById('ficha').textContent = data.ficha || "";
                    document.getElementById('nivel_formacion').textContent = data.nivel_formacion || "";
                    document.getElementById('nombre_programa').textContent = data.nombre_programa || "";
                    document.getElementById('descripcion').textContent = data.descripcion || "";
                    document.getElementById('habilidades_requeridas').textContent = data.habilidades_requeridas || "";
                    document.getElementById('fecha_finalizacion').textContent = data.fecha_finalizacion || "";
                    document.getElementById('estado').textContent = data.estado || "";
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del programa:', error);
            });
    }
});
