<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink-Formulario</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="../img/Favicon1.png">
    <link rel="stylesheet" href="../css/crud.css">
</head>
<body>
    <div class="container">
        <form id="form-diagnostico" class="container__crud">
            <img src="../img/logo-proyecto1.png" alt="" class="logo__senalink">

            <!-- Sección para mostrar si ya completó el diagnóstico -->
            <div id="diagnostico-completado" class="hidden">
                <h2>Ya has completado el diagnóstico</h2>
                <div class="filtros-recomendaciones">
                    <h3>Filtrar recomendaciones</h3>
                    <div class="filtros-container">
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnico" checked> Técnico
                        </label>
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnologo" checked> Tecnólogo
                        </label>
                        <select name="duracion" class="input-field">
                            <option value="all">Cualquier duración</option>
                            <option value="6">Hasta 6 meses</option>
                            <option value="12">Hasta 1 año</option>
                            <option value="24">Más de 1 año</option>
                        </select>
                        <button id="aplicar-filtros" class="btn-small">Aplicar</button>
                    </div>
                </div>
                <div id="recomendaciones-guardadas" class="recomendaciones-container"></div>
                <div class="form__buttons">
                    <button type="button" onclick="goBack()" class="btn">Volver</button>
                </div>
            </div>

            <!-- Sección del formulario -->
            <div id="formulario-diagnostico">
                <h2>Diagnóstico Empresarial</h2>
                <p>Por favor responde las siguientes preguntas para recibir recomendaciones personalizadas</p>
                <div id="preguntas-dinamicas">
                    <!-- Las preguntas se cargarán dinámicamente aquí -->
                </div>
                <div class="form__buttons"> 
                    <?php if (isset($_GET['from']) && $_GET['from'] === 'gestion'): ?>
                        <a href="Super_Admin/Diagnostico/VerDiagnostico.html" id="btnGestion" class="btn">Gestión</a>
                    <?php endif; ?>
                    <button type="button" id="btn-enviar" class="btn">Enviar Respuestas</button>
                    <button type="button" onclick="goBack()" class="btn">Volver</button>
                </div>
            </div>

            <!-- Sección de resultados -->
            <div id="resultados-diagnostico" class="hidden">
                <h2>Recomendaciones de Programas</h2>
                <div class="filtros-recomendaciones">
                    <h3>Filtrar recomendaciones</h3>
                    <div class="filtros-container">
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnico" checked> Técnico
                        </label>
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnologo" checked> Tecnólogo
                        </label>
                        <select name="duracion" class="input-field">
                            <option value="all">Cualquier duración</option>
                            <option value="6">Hasta 6 meses</option>
                            <option value="12">Hasta 1 año</option>
                            <option value="24">Más de 1 año</option>
                        </select>
                        <button id="aplicar-filtros" class="btn-small">Aplicar</button>
                    </div>
                </div>
                <div id="recomendaciones" class="recomendaciones-container"></div>
                <div class="form__buttons">
                    <button type="button" onclick="goBack()" class="btn">Finalizar</button>
                </div>
            </div>
        </form>
    </div>


    <script>
         var empresaId = <?php echo isset($_SESSION['empresa_id']) ? intval($_SESSION['empresa_id']) : 'null'; ?>;
class DiagnosticoApp {
    
    constructor() {
        this.initElements();
        this.initEvents();
        this.cargarPreguntasDiagnostico();
    }

    initElements() {
        this.elements = {
            formularioDiagnostico: document.getElementById('formulario-diagnostico'),
            resultadosDiagnostico: document.getElementById('resultados-diagnostico'),
            preguntasDinamicas: document.getElementById('preguntas-dinamicas'),
            recomendaciones: document.getElementById('recomendaciones'),
            btnEnviar: document.getElementById('btn-enviar')
        };
    }

    initEvents() {
        this.elements.btnEnviar.addEventListener('click', () => this.enviarRespuestas());
        document.querySelectorAll('#aplicar-filtros').forEach(btn => {
            btn.classList.add('btn', 'btn-small'); // Asegura el estilo correcto
            btn.addEventListener('click', (e) => this.aplicarFiltros(e));
        });
    }
    
    async cargarPreguntasDiagnostico() {
        try {
            const data = await this.fetchData('obtenerDiagnosticoCompleto');
            if (data.success && data.preguntas) {
                this.renderPreguntasDinamicas(data.preguntas);
            } else {
                this.showError('No se pudieron cargar las preguntas.', 'preguntas-dinamicas');
            }
        } catch (error) {
            console.error('Error cargando preguntas:', error);
            this.showError('Error al cargar preguntas.', 'preguntas-dinamicas');
        }
    }

    async enviarRespuestas() {
        const { selects, inputs, respuestas, todasRespondidas } = this.validarRespuestas();
        if (!todasRespondidas) {
            this.showError('Por favor responde todas las preguntas');
            return;
        }

        try {
            const data = await this.fetchData('procesarRespuestas', {
                empresaId: empresaId,
                respuestas: respuestas
            });

            if (data.success) {
                this.showSection('resultados-diagnostico');
                this.renderRecomendaciones(data.recomendaciones, 'recomendaciones');
                localStorage.setItem('recomendaciones', JSON.stringify(data.recomendaciones));
                window.location.href = 'Empresa/Home.html';
            } else {
                this.showError(data.message || 'Error al procesar respuestas');
            }
        } catch (error) {
            console.error('Error enviando respuestas:', error);
            this.showError('Error al enviar las respuestas.');
        }
    }

    validarRespuestas() {
        const selects = this.elements.preguntasDinamicas.querySelectorAll('select');
        const inputs = this.elements.preguntasDinamicas.querySelectorAll('input[type="text"]');
        const respuestas = {};
        let todasRespondidas = true;

        selects.forEach((select, index) => {
            if (!select.value || select.value === 'Seleccione una opción') {
                todasRespondidas = false;
                select.classList.add('error');
            } else {
                respuestas[`pregunta${index + 1}`] = select.value;
                select.classList.remove('error');
            }
        });

        inputs.forEach((input, index) => {
            if (!input.value.trim()) {
                todasRespondidas = false;
                input.classList.add('error');
            } else {
                respuestas[`pregunta${selects.length + index + 1}`] = input.value.trim();
                input.classList.remove('error');
            }
        });

        return { selects, inputs, respuestas, todasRespondidas };
    }

    aplicarFiltros(event) {
        try {
            const btn = event.target;
            const contenedor = btn.closest('.recomendaciones-container');
            const contenedorId = contenedor.id;
            const filtrosContainer = document.getElementById(`${contenedorId}-container`);

            const filtros = {
                nivel: Array.from(filtrosContainer.querySelectorAll('input[name="nivel"]:checked')).map(el => el.value),
                duracion: filtrosContainer.querySelector('select[name="duracion"]').value
            };

            const cards = contenedor.querySelectorAll('.recomendacion-card');
            cards.forEach(card => {
                const nivel = card.dataset.nivel;
                const duracionMeses = parseInt(card.dataset.duracion) / 48;

                const cumpleNivel = filtros.nivel.includes(nivel);
                const cumpleDuracion = filtros.duracion === 'all' ||
                    (filtros.duracion === '6' && duracionMeses <= 6) ||
                    (filtros.duracion === '12' && duracionMeses <= 12) ||
                    (filtros.duracion === '24' && duracionMeses <= 24);

                card.style.display = cumpleNivel && cumpleDuracion ? 'block' : 'none';
            });
        } catch (error) {
            console.error('Error aplicando filtros:', error);
        }
    }

    renderPreguntasDinamicas(preguntas) {
        this.elements.preguntasDinamicas.innerHTML = '';
        preguntas.forEach(pregunta => {
            const div = this.crearElementoPregunta(pregunta);
            this.elements.preguntasDinamicas.appendChild(div);
        });
    }

    crearElementoPregunta(pregunta) {
        const preguntaDiv = document.createElement('div');
        preguntaDiv.className = 'pregunta-container';

        const label = document.createElement('label');
        label.textContent = pregunta.enunciado;
        preguntaDiv.appendChild(label);

        if (pregunta.opciones?.length > 0) {
            preguntaDiv.appendChild(this.crearSelectOpciones(pregunta.opciones));
        } else {
            preguntaDiv.appendChild(this.crearInputTexto());
        }

        return preguntaDiv;
    }

    crearSelectOpciones(opciones) {
        const select = document.createElement('select');
        select.required = true;
        select.classList.add('input-field'); // Aplica el estilo de crud.css

        const optDefault = document.createElement('option');
        optDefault.value = '';
        optDefault.selected = true;
        optDefault.disabled = true;
        optDefault.textContent = 'Seleccione una opción';
        select.appendChild(optDefault);

        opciones.forEach(op => {
            const option = document.createElement('option');
            option.value = op.texto;
            option.textContent = op.texto;
            select.appendChild(option);
        });

        return select;
    }

    crearInputTexto() {
        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = 'Ingrese su respuesta';
        input.required = true;
        input.classList.add('input-field'); // Aplica el estilo de crud.css
        return input;
    }

    renderRecomendaciones(recomendaciones, contenedorId) {
        const cont = this.elements[contenedorId] || document.getElementById(contenedorId);
        cont.classList.remove('hidden');
        cont.innerHTML = '';

        if (!recomendaciones?.length) {
            this.showInfo('No encontramos programas que coincidan con tus necesidades.', contenedorId);
            return;
        }

        const grupos = this.agruparPorArea(recomendaciones);
        for (const [area, programas] of Object.entries(grupos)) {
            const grupoDiv = this.crearGrupoArea(area, programas);
            cont.appendChild(grupoDiv);
        }

        this.configurarBotonesDetalle();
    }

    agruparPorArea(recomendaciones) {
        const grupos = {};
        recomendaciones.forEach(prog => {
            const area = prog.sector_economico || 'General';
            if (!grupos[area]) grupos[area] = [];
            grupos[area].push(prog);
        });
        return grupos;
    }

    crearGrupoArea(area, programas) {
        const grupoDiv = document.createElement('div');
        grupoDiv.className = 'grupo-area';
        grupoDiv.innerHTML = `<h3 class="area-titulo">${area}</h3>`;
        programas.forEach(prog => grupoDiv.appendChild(this.crearCardPrograma(prog)));
        return grupoDiv;
    }

    crearCardPrograma(prog) {
        const meses = (prog.duracion_programa / 48).toFixed(1);
        const card = document.createElement('div');
        card.className = 'recomendacion-card';
        card.dataset.nivel = prog.nivel_formacion;
        card.dataset.duracion = prog.duracion_programa;

        card.innerHTML = `
            <div class="card-header">
                <h3>${prog.nombre_programa}</h3>
                <span class="badge ${prog.nivel_formacion === 'Tecnologo' ? 'badge-tecnologo' : 'badge-tecnico'}">
                    ${prog.nivel_formacion}
                </span>
            </div>
            <div class="card-body">
                <p><strong>Duración:</strong> ${meses} meses</p>
                <p><strong>Ocupación:</strong> ${prog.nombre_ocupacion}</p>
                <p><strong>Hasta:</strong> ${new Date(prog.fecha_finalizacion).toLocaleDateString()}</p>
            </div>`;
        return card;
    }

    configurarBotonesDetalle() {
        document.querySelectorAll('.btn-detalle').forEach(btn => {
            btn.classList.add('btn'); // Asegura el estilo de botón
            btn.addEventListener('click', (e) => {
                const id = e.target.dataset.id;
                console.log('Ver detalle de programa:', id);
            });
        });
    }

    async fetchData(action, extraData = {}) {
        const res = await fetch('../controllers/DiagnosticoController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: action, ...extraData })
        });
        return await res.json();
    }

    showSection(sectionId) {
        Object.values(this.elements).forEach(el => {
            if (el?.classList) {
                el.classList.add('hidden');
            }
        });

        const seccion = this.elements[sectionId] || document.getElementById(sectionId);
        if (seccion) {
            seccion.classList.remove('hidden');
            seccion.scrollIntoView({ behavior: 'smooth' });
        }
    }

    showError(message, containerId = null) {
        const container = containerId ? (this.elements[containerId] || document.getElementById(containerId)) : this.elements.preguntasDinamicas;
        container.innerHTML = `<div class="error-message"><p>${message}</p></div>`;
    }

    showInfo(message, containerId) {
        const container = this.elements[containerId] || document.getElementById(containerId);
        container.innerHTML = `<div class="info-message"><p>${message}</p></div>`;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('diagnostico-completado').classList.add('hidden');
    document.getElementById('resultados-diagnostico').classList.add('hidden');
    document.getElementById('formulario-diagnostico').classList.remove('hidden');
    new DiagnosticoApp();

    // Si se usa el flag desdeGestion, agrega el botón con clase btn
    if (sessionStorage.getItem("desdeGestion") === "1") {
        const formButtons = document.querySelector('.form__buttons');
        if (formButtons) {
            const gestionBtn = document.createElement("button");
            gestionBtn.textContent = "Gestión";
            gestionBtn.className = "btn";
            gestionBtn.onclick = function (e) {
                e.preventDefault();
                window.location.href = "Super_Admin/Diagnostico/VerDiagnostico.html";
            };
            formButtons.appendChild(gestionBtn);
        }
        sessionStorage.removeItem("desdeGestion");
    }
});

// Función global para volver atrás
function goBack() {
    window.history.back();
}
    </script>
</body>
</html>