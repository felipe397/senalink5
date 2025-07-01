document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');

    // Nuevos botones de filtro
    const filtroDisponible = document.getElementById('filtro-disponible');
    const filtroEnCurso = document.getElementById('filtro-en-curso');
    const filtroFinalizado = document.getElementById('filtro-finalizado');

    let programas = [];

    // Leer el estado desde la URL
    const params = new URLSearchParams(window.location.search);
    let estadoActual = 'disponible'; // Valor por defecto
    if (params.has('estado')) {
        const estadoParam = params.get('estado').toLowerCase();
        if (['disponible', 'en curso', 'finalizado'].includes(estadoParam)) {
            estadoActual = estadoParam;
        }
    }

    if (container && inputBusqueda) {
        function cargarProgramasPorEstado(estado) {
            estadoActual = estado.toLowerCase();
            let url = "";

            switch (estadoActual) {
                case "disponible":
                    url = "../../../controllers/ProgramaController.php?action=listarProgramasDisponibles";
                    break;
                case "en curso":
                    url = "../../../controllers/ProgramaController.php?action=listarProgramasEnCurso";
                    break;
                case "finalizado":
                    url = "../../../controllers/ProgramaController.php?action=listarProgramasFinalizados";
                    break;
                default:
                    console.error("Estado no válido:", estadoActual);
                    return;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    programas = data;
                    renderProgramas(programas);
                })
                .catch(error => {
                    console.error("Error al cargar los programas:", error);
                });
        }

        // Cargar programas inicialmente
        cargarProgramasPorEstado(estadoActual);

        // Buscar por nombre o ficha
        inputBusqueda.addEventListener("input", function () {
            const q = this.value.toLowerCase();
            if (q.length >= 1) {
                const filtrados = programas.filter(p =>
                    p.nombre_programa.toLowerCase().includes(q) ||
                    p.ficha.toLowerCase().includes(q)
                );
                renderProgramas(filtrados);
            } else {
                renderProgramas(programas);
            }
        });

        // Filtros nuevos
        if (filtroDisponible && filtroEnCurso && filtroFinalizado) {
            filtroDisponible.addEventListener('click', function () {
                cargarProgramasPorEstado('disponible');
            });
            filtroEnCurso.addEventListener('click', function () {
                cargarProgramasPorEstado('en curso');
            });
            filtroFinalizado.addEventListener('click', function () {
                cargarProgramasPorEstado('finalizado');
            });
        }

        function renderProgramas(programas) {
            container.innerHTML = "";
            if (programas.length === 0) {
                container.innerHTML = "<p>No se encontraron programas.</p>";
                return;
            }
            programas.forEach(programa => {
                const card = document.createElement("article");
                card.classList.add("cardh");
                card.innerHTML = `
                    <div class="card-text">
                        <h2 class="card-title">${programa.nombre_programa}</h2>
                        <p class="card-subtitle">Código: ${programa.ficha}</p>
                    </div>
                `;
                card.style.cursor = 'pointer';
                card.addEventListener('click', function () {
                    window.location.href = `programa de formacion.html?id=${programa.id}&estado=${estadoActual}`;
                });
                container.appendChild(card);
            });
        }

        // Recarga externa
        window.recargarProgramas = () => cargarProgramasPorEstado(estadoActual);
    }

    // Parte del detalle del programa
    const id = new URLSearchParams(window.location.search).get('id');
    if (id) {
        fetch(`../../../controllers/ProgramaController.php?action=DetallePrograma&id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert("Error al obtener los detalles del programa: " + data.error);
                } else if (!data || Object.keys(data).length === 0) {
                    alert("No se encontraron datos para el programa solicitado.");
                } else {
                    const campos = ['codigo', 'ficha', 'nivel_formacion', 'sector_programa', 'nombre_programa', 'descripcion', 'habilidades_requeridas', 'fecha_finalizacion', 'estado'];
                    campos.forEach(campo => {
                        const elemento = document.getElementById(campo);
                        if (elemento) {
                            elemento.textContent = data[campo] || "";
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
