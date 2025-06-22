document.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    let programas = [];

    // Solo ejecutar la parte de lista si existe el contenedor
    if (container) {
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

    // Buscar en tiempo real solo si existe el inputBusqueda
    if (inputBusqueda) {
        inputBusqueda.addEventListener("input", function () {
            const q = this.value.trim().toLowerCase();
            if (q.length >= 1) {
                const filtrados = programas.filter(p =>
                    p.nombre_programa?.toLowerCase().startsWith(q) ||
                    p.ficha?.toLowerCase().startsWith(q) ||
                    p.codigo?.toLowerCase().startsWith(q)
                );
                renderProgramas(filtrados);
            } else {
                renderProgramas(programas);
            }
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
    } else {
        console.warn("No se encontró el contenedor con clase .cardh__container");
    }

    // Ejecutar siempre la parte de detalle del programa
    const id = new URLSearchParams(window.location.search).get('id');
    console.log("ID obtenido de la URL:", id);
    if (id) {
        fetch(`../../../controllers/UsuarioController.php?action=DetallePrograma&id=${id}`)
            .then(response => {
                console.log("Respuesta HTTP del fetch DetallePrograma:", response);
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Datos recibidos del fetch DetallePrograma:", data);
                if (data.error) {
                    console.error(data.error);
                    alert("Error al obtener los detalles del programa: " + data.error);
                } else if (!data || Object.keys(data).length === 0) {
                    console.warn("No se encontraron datos para el programa con id:", id);
                    alert("No se encontraron datos para el programa solicitado.");
                } else {
                    const campos = ['codigo', 'ficha', 'nivel_formacion', 'nombre_programa', 'descripcion', 'habilidades_requeridas', 'fecha_finalizacion', 'estado'];
                    campos.forEach(campo => {
                        const elemento = document.getElementById(campo);
                        if (elemento) {
                            elemento.textContent = data[campo] || "";
                        } else {
                            console.warn(`Elemento con id '${campo}' no encontrado en el DOM.`);
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del programa:', error);
                alert("Error al cargar los detalles del programa. Por favor, intenta nuevamente.");
            });
    }
});
