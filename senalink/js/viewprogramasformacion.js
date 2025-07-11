document.addEventListener("DOMContentLoaded", function () {
    const container = document.querySelector(".cardh__container");
    // Buscar el input de búsqueda por clase específica si existe, si no, usar el primero dentro de search-container
    let inputBusqueda = document.querySelector('.input-busqueda');
    if (!inputBusqueda) {
        inputBusqueda = document.querySelector('.search-container input');
    }
    const filtroEjecucion = document.getElementById('filtro-ejecucion');
    const filtroFinalizado = document.getElementById('filtro-finalizado');
    const contexto = document.body.dataset.context || "vista1";

    const programasPorPagina = 6;
    let programas = [];
    let paginaActual = 1;

    // Determinar estado por URL solo si existe
    const estadoEnUrl = new URLSearchParams(window.location.search).get('estado');
    let estadoActual = estadoEnUrl === 'Finalizado' ? 'Finalizado' : 'En ejecucion';

    function obtenerUrlListado(estado) {
        const base = contexto === "vista1" ? "../../" : "../../../";
        return base + "controllers/ProgramaController.php?action=" +
            (estado === "En ejecucion" ? "listarProgramasEnEjecucion" : "listarProgramasFinalizados");
    }

    function cargarProgramasPorEstado(estado) {
        estadoActual = estado;
        const url = obtenerUrlListado(estado);
        if (!url) return;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data)) {
                    programas = [];
                    renderProgramas([]);
                    return;
                }
                programas = data;
                paginaActual = 1;
                renderProgramas(filtrarPorBusqueda(programas));
                crearBotonesPaginacion();
            })
            .catch(error => {
                console.error("Error al cargar los programas:", error);
            });
    }

    function filtrarPorBusqueda(lista) {
        const q = inputBusqueda?.value.trim().toLowerCase();
        if (!q) return lista;
        return lista.filter(p =>
            (p.nombre_programa && p.nombre_programa.toLowerCase().includes(q)) ||
            (p.codigo && p.codigo.toLowerCase().includes(q))
        );
    }

    function renderProgramas(lista) {
        if (!container) return;
        container.innerHTML = "";
        const inicio = (paginaActual - 1) * programasPorPagina;
        const fin = inicio + programasPorPagina;
        const paginaProgramas = lista.slice(inicio, fin);

        if (paginaProgramas.length === 0) {
            container.innerHTML = "<p>No se encontraron programas.</p>";
            return;
        }

        paginaProgramas.forEach(programa => {
            const card = document.createElement("article");
            card.classList.add("cardh");
            card.innerHTML = `
                <div class="card-text">
                    <h2 class="card-title">${programa.nombre_programa}</h2>
                    <p class="card-subtitle">Código: ${programa.codigo}</p>
                </div>
            `;
            card.style.cursor = 'pointer';
            card.addEventListener('click', () => {
                window.location.href = `programa de formacion.html?id=${programa.id}&estado=${estadoActual}`;
            });
            container.appendChild(card);
        });
    }

    function crearBotonesPaginacion() {
        const paginacionExistente = document.querySelector(".paginacion");
        if (paginacionExistente) paginacionExistente.remove();

        const listaFiltrada = filtrarPorBusqueda(programas);
        const totalPaginas = Math.ceil(listaFiltrada.length / programasPorPagina);
        if (totalPaginas <= 1 || !container) return;

        const paginacion = document.createElement("div");
        paginacion.classList.add("paginacion");

        for (let i = 1; i <= totalPaginas; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            if (i === paginaActual) btn.classList.add("activo");

            btn.addEventListener("click", () => {
                paginaActual = i;
                renderProgramas(listaFiltrada);
                crearBotonesPaginacion();
            });

            paginacion.appendChild(btn);
        }

        container.parentElement.appendChild(paginacion);
    }

    // Eventos solo si existen los elementos
    if (inputBusqueda) {
        inputBusqueda.addEventListener("input", () => {
            paginaActual = 1;
            const filtrados = filtrarPorBusqueda(programas);
            renderProgramas(filtrados);
            crearBotonesPaginacion();
        });
    }

    if (filtroEjecucion && filtroFinalizado) {
        filtroEjecucion.addEventListener('click', () => cargarProgramasPorEstado('En ejecucion'));
        filtroFinalizado.addEventListener('click', () => cargarProgramasPorEstado('Finalizado'));
    }

    // Si hay contenedor de tarjetas, asumimos que es la vista de lista
    if (container) {
        cargarProgramasPorEstado(estadoActual);
    }

    // Vista detalle
    const id = new URLSearchParams(window.location.search).get('id');
    if (id) {
        const urlDetalle = contexto === "vista1"
            ? "../../controllers/ProgramaController.php"
            : "../../../controllers/ProgramaController.php";

        fetch(urlDetalle, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                accion: 'detallePrograma',
                id: id
            })
        })
            .then(response => {
                if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (!data.success || !data.programa) {
                    alert("No se encontraron datos para el programa solicitado.");
                } else {
                    const p = data.programa;
                    const campos = [
                        'codigo', 'ficha', 'nivel_formacion', 'sector_programa',
                        'etapa_ficha', 'sector_economico', 'nombre_ocupacion',
                        'nombre_programa', 'habilidades_requeridas',
                        'duracion_programa', 'fecha_finalizacion', 'estado'
                    ];
                    campos.forEach(campo => {
                        const el = document.getElementById(campo);
                        if (el) el.textContent = p[campo] || "";
                    });

                    // Ocultar botón "Actualizar" si estado es "Finalizado"
                    const btnActualizar = document.getElementById('btnActualizar');
                    if (btnActualizar && p.estado && p.estado.toLowerCase() === 'finalizado') {
                        btnActualizar.style.display = 'none';
                    } else if (btnActualizar) {
                        btnActualizar.style.display = 'inline-block';
                    }
                }
            })
            .catch(error => {
                console.error('Error al cargar los detalles del programa:', error);
                alert("Error al cargar los detalles del programa. Por favor, intenta nuevamente.");
            });
    }
    // Botón de reporte para AdminSENA
    const btnReporte = document.getElementById('ProgReporte');
    if (btnReporte && id) {
        btnReporte.addEventListener('click', function (e) {
            e.preventDefault();
            // Redirigir a la vista de reporte HTML del programa
            window.location.href = `ProgramaReport.html?id=${id}`;
        });
    }
});
