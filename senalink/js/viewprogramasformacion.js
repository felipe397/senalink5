document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');

    // Nuevos botones de filtro (ajustados a los nuevos ids y estados)
    const filtroEjecucion = document.getElementById('filtro-ejecucion');
    const filtroFinalizado = document.getElementById('filtro-finalizado');

    let programas = [];

    // Leer el estado desde la URL
    const params = new URLSearchParams(window.location.search);
    let estadoActual = 'En ejecucion'; // Valor por defecto
    if (params.has('estado')) {
        const estadoParam = params.get('estado');
        if (["En ejecucion", "Finalizado"].includes(estadoParam)) {
            estadoActual = estadoParam;
        }
    }

    if (container && inputBusqueda) {
        function cargarProgramasPorEstado(estado) {
            estadoActual = estado;
            let url = "";
            switch (estadoActual) {
                case "En ejecucion":
                    url = "../../../controllers/ProgramaController.php?action=listarProgramasEnEjecucion";
                    break;
                case "Finalizado":
                    url = "../../../controllers/ProgramaController.php?action=listarProgramasFinalizados";
                    break;
                default:
                    console.error("Estado no válido:", estadoActual);
                    return;
            }
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data && data.error) {
                        alert("Error: " + data.error);
                        programas = [];
                        renderProgramas(programas);
                        return;
                    }
                    if (!Array.isArray(data)) {
                        programas = [];
                        renderProgramas(programas);
                        return;
                    }
                    programas = data;
                    renderProgramas(programas);
                })
                .catch(error => {
                    console.error("Error al cargar los programas:", error);
                });
        }

        // Cargar programas inicialmente
        cargarProgramasPorEstado(estadoActual);

        // Buscar por nombre o ficha solo en el estado filtrado
        inputBusqueda.addEventListener("input", function () {
            const q = this.value.toLowerCase();
            if (q.length >= 1) {
                const filtrados = programas.filter(p =>
                    (p.nombre_programa && p.nombre_programa.toLowerCase().includes(q)) ||
                    (p.codigo && p.codigo.toLowerCase().includes(q))
                );
                renderProgramas(filtrados);
            } else {
                renderProgramas(programas);
            }
        });

        // Filtros nuevos (ajustados a los nuevos ids y estados)
        if (filtroEjecucion && filtroFinalizado) {
            filtroEjecucion.addEventListener('click', function () {
                cargarProgramasPorEstado('En ejecucion');
            });
            filtroFinalizado.addEventListener('click', function () {
                cargarProgramasPorEstado('Finalizado');
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
                        <p class="card-subtitle">Código: ${programa.codigo}</p>
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
                    const campos = ['codigo', 'ficha', 'nivel_formacion', 'sector_programa', 'etapa_ficha','sector_economico', 'nombre_ocupacion','nombre_programa', 'habilidades_requeridas', 'duracion_programa','fecha_finalizacion', 'estado'];
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
