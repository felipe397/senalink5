document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    const inputBusqueda = document.querySelector('.search-container input');
    const filtroActivo = document.getElementById('filtro-activo');
    const filtroDesactivado = document.getElementById('filtro-desactivado');
    let programas = [];

    // Leer el estado desde la URL
    const params = new URLSearchParams(window.location.search);
    let estadoActual = 'Activo';
    if (params.has('estado')) {
        const estadoParam = params.get('estado');
        if (estadoParam.toLowerCase() === 'desactivado' || estadoParam.toLowerCase() === 'inactivo') {
            estadoActual = 'Desactivado';
        }
    }

    if (container && inputBusqueda) {
        // Cargar programas según el estado
        function cargarProgramasPorEstado(estado) {
            estadoActual = estado;
            let url = "";
            if (estado === "Activo") {
                url = "../../../controllers/ProgramaController.php?action=listarProgramasActivos";
            } else {
                url = "../../../controllers/ProgramaController.php?action=listarProgramasInhabilitados";
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

        // Buscar por nombre o código
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

        // Filtros
        if (filtroActivo && filtroDesactivado) {
            filtroActivo.addEventListener('click', function () {
                cargarProgramasPorEstado('Activo');
            });
            filtroDesactivado.addEventListener('click', function () {
                cargarProgramasPorEstado('Desactivado');
            });
        }

        // Renderizar tarjetas
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
                    if (estadoActual === 'Activo') {
                        window.location.href = `Programa.html?id=${programa.id}`;
                    } else {
                        window.location.href = `ProgramaInhabilitado.html?id=${programa.id}`;
                    }
                });
                container.appendChild(card);
            });
        }

        // Recarga externa
        window.recargarProgramas = () => cargarProgramasPorEstado(estadoActual);
    }
    // Ejecutar siempre la parte de detalle del programa
    const id = new URLSearchParams(window.location.search).get('id');
    console.log("ID obtenido de la URL:", id);
    if (id) {
        fetch(`../../../controllers/ProgramaController.php?action=DetallePrograma&id=${id}`)
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
                    const campos = ['codigo', 'ficha', 'nivel_formacion', 'sector_programa', 'nombre_programa', 'descripcion', 'habilidades_requeridas', 'fecha_finalizacion', 'estado'];
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
