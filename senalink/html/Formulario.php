<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink-Formulario</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=account_circle" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon " href="../img/Favicon1.png">
    <style>
        /* Estilos base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .hidden {
            display: none;
        }
        
        /* Estilos para formulario */
        .container__forms {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .logo__senalink {
            max-width: 200px;
            margin: 0 auto;
            display: block;
        }
        
        /* Estilos para preguntas */
        fieldset {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        legend {
            font-weight: bold;
            color: #2c3e50;
            padding: 0 10px;
        }
        
        .select_container, .input-field {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        /* Estilos para botones */
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-small {
            padding: 5px 10px;
            font-size: 14px;
        }
        
        /* Estilos para recomendaciones */
        .recomendaciones-container {
            margin-top: 30px;
        }
        
        .grupo-area {
            margin-bottom: 30px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 20px;
        }
        
        .area-titulo {
            color: #2c3e50;
            border-left: 4px solid #3498db;
            padding-left: 10px;
            margin: 20px 0;
        }
        
        .recomendacion-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .recomendacion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .badge-tecnico {
            background: #3498db;
            color: white;
        }
        
        .badge-tecnologo {
            background: #2ecc71;
            color: white;
        }
        
        .btn-detalle {
            background: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        /* Filtros */
        .filtros-container {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        
        .filtros-container label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Sin resultados */
        .no-results {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .no-results i {
            font-size: 40px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .btn-contacto {
            display: inline-block;
            margin-top: 10px;
            background: #e74c3c;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="form-diagnostico" class="container__forms">
            <img src="../img/logo-proyecto1.png" alt="" class="logo__senalink">
            
            <!-- Sección para mostrar si ya completó el diagnóstico -->
            <div id="diagnostico-completado" class="hidden">
                <h2>Ya has completado el diagnóstico</h2>
                <div id="filtros-recomendaciones">
                    <h3>Filtrar recomendaciones</h3>
                    <div class="filtros-container">
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnico" checked> Técnico
                        </label>
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnologo" checked> Tecnólogo
                        </label>
                        <select name="duracion">
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
                    <button type="button" id="btn-enviar" class="btn">Enviar Respuestas</button>
                    <button type="button" onclick="goBack()" class="btn">Volver</button>
                </div>
            </div>
            
            <!-- Sección de resultados -->
            <div id="resultados-diagnostico" class="hidden">
                <h2>Recomendaciones de Programas</h2>
                <div id="filtros-recomendaciones">
                    <h3>Filtrar recomendaciones</h3>
                    <div class="filtros-container">
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnico" checked> Técnico
                        </label>
                        <label>
                            <input type="checkbox" name="nivel" value="Tecnologo" checked> Tecnólogo
                        </label>
                        <select name="duracion">
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
    // Obtener ID de empresa desde sesión
const empresaId = <?php echo json_encode($_SESSION['user_id'] ?? 0); ?>;

class DiagnosticoApp {
    constructor() {
        this.initElements();
        this.initEvents();
        this.checkDiagnosticoStatus();
    }

    initElements() {
        // Elementos principales
        this.elements = {
            diagnosticoCompletado: document.getElementById('diagnostico-completado'),
            formularioDiagnostico: document.getElementById('formulario-diagnostico'),
            resultadosDiagnostico: document.getElementById('resultados-diagnostico'),
            preguntasDinamicas: document.getElementById('preguntas-dinamicas'),
            recomendacionesGuardadas: document.getElementById('recomendaciones-guardadas'),
            recomendaciones: document.getElementById('recomendaciones'),
            btnEnviar: document.getElementById('btn-enviar')
        };
    }

    initEvents() {
        // Configurar eventos
        this.elements.btnEnviar.addEventListener('click', () => this.enviarRespuestas());
        
        // Configurar filtros para ambas secciones de recomendaciones
        document.querySelectorAll('#aplicar-filtros').forEach(btn => {
            btn.addEventListener('click', (e) => this.aplicarFiltros(e));
        });
    }

    async checkDiagnosticoStatus() {
        try {
            const data = await this.fetchData('diagnosticoCompletado', { empresaId });
            
            if (data.completado) {
                this.showSection('diagnostico-completado');
                this.cargarRecomendacionesGuardadas();
            } else {
                this.cargarPreguntasDiagnostico();
            }
        } catch (error) {
            console.error('Error verificando estado del diagnóstico:', error);
            this.showError('No se pudo verificar el estado del diagnóstico. Intenta recargar la página.');
        }
    }

    async cargarPreguntasDiagnostico() {
        try {
            const data = await this.fetchData('obtenerDiagnosticoCompleto');
            
            if (data.success && data.preguntas) {
                this.renderPreguntasDinamicas(data.preguntas);
            } else {
                this.showError('No se pudieron cargar las preguntas. Por favor intenta más tarde.', 'preguntas-dinamicas');
            }
        } catch (error) {
            console.error('Error cargando preguntas:', error);
            this.showError('Error al cargar las preguntas del diagnóstico.', 'preguntas-dinamicas');
        }
    }

    async cargarRecomendacionesGuardadas() {
        try {
            const recomendaciones = await this.fetchData('obtenerRecomendaciones', { empresaId });
            
            if (recomendaciones.length > 0) {
                this.renderRecomendaciones(recomendaciones, 'recomendaciones-guardadas');
            } else {
                this.showInfo('No se encontraron recomendaciones guardadas.', 'recomendaciones-guardadas');
            }
        } catch (error) {
            console.error('Error cargando recomendaciones:', error);
            this.showError('Error al cargar recomendaciones guardadas.', 'recomendaciones-guardadas');
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
                empresaId, 
                respuestas 
            });
            
            if (data.success) {
                this.showSection('resultados-diagnostico');
                this.renderRecomendaciones(data.recomendaciones, 'recomendaciones');
            } else {
                this.showError(data.message || 'Error al procesar respuestas');
            }
        } catch (error) {
            console.error('Error enviando respuestas:', error);
            this.showError('Error al enviar las respuestas. Por favor intenta nuevamente.');
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

    // Reemplaza tu función aplicarFiltros con esta versión mejorada
plicarFiltros(event) {
    try {
        const btn = event.target;
        const contenedor = btn.closest('.recomendaciones-container');
        
        if (!contenedor) {
            console.error('No se encontró el contenedor de recomendaciones');
            return;
        }
        
        const contenedorId = contenedor.id;
        const filtrosContainer = document.getElementById(`${contenedorId}-container`);
        
        if (!filtrosContainer) {
            console.error('No se encontró el contenedor de filtros');
            return;
        }
        
        const filtros = {
            nivel: Array.from(filtrosContainer.querySelectorAll('input[name="nivel"]:checked')).map(el => el.value),
            duracion: filtrosContainer.querySelector('select[name="duracion"]').value
        };
        
        const cards = document.querySelectorAll(`#${contenedorId} .recomendacion-card`);
        
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
        console.error('Error al aplicar filtros:', error);
    }
}

    getFiltrosActuales(contenedorId) {
        return {
            nivel: Array.from(document.querySelectorAll(
                `#${contenedorId}-container input[name="nivel"]:checked`
            )).map(el => el.value),
            duracion: document.querySelector(
                `#${contenedorId}-container select[name="duracion"]`
            ).value
        };
    }

    cumpleFiltroDuracion(filtroDuracion, duracionMeses) {
        switch(filtroDuracion) {
            case '6': return duracionMeses <= 6;
            case '12': return duracionMeses <= 12;
            case '24': return duracionMeses <= 24;
            default: return true;
        }
    }

    renderPreguntasDinamicas(preguntas) {
        this.elements.preguntasDinamicas.innerHTML = '';
        
        const fieldsetGeneral = document.createElement('fieldset');
        fieldsetGeneral.innerHTML = '<legend>Datos Generales de la Empresa</legend>';
        
        const fieldsetNecesidades = document.createElement('fieldset');
        fieldsetNecesidades.innerHTML = '<legend>Necesidades de Formación</legend>';
        
        preguntas.forEach((pregunta, index) => {
            const preguntaDiv = this.crearElementoPregunta(pregunta);
            
            if (index < 3) {
                fieldsetGeneral.appendChild(preguntaDiv);
            } else {
                fieldsetNecesidades.appendChild(preguntaDiv);
            }
        });
        
        this.elements.preguntasDinamicas.appendChild(fieldsetGeneral);
        this.elements.preguntasDinamicas.appendChild(fieldsetNecesidades);
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
        
        const optDefault = document.createElement('option');
        optDefault.value = '';
        optDefault.selected = true;
        optDefault.disabled = true;
        optDefault.textContent = 'Seleccione una opción';
        select.appendChild(optDefault);
        
        opciones.forEach(opc => {
            const opt = document.createElement('option');
            opt.value = opc.texto;
            opt.textContent = opc.texto;
            select.appendChild(opt);
        });
        
        return select;
    }

    crearInputTexto() {
        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = 'Ingrese su respuesta';
        input.required = true;
        return input;
    }

    renderRecomendaciones(recomendaciones, contenedorId) {
        const cont = this.elements[contenedorId] || document.getElementById(contenedorId);
        cont.innerHTML = '';
        
        if (!recomendaciones?.length) {
            this.showInfo('No encontramos programas que coincidan exactamente con tus necesidades.', contenedorId);
            return;
        }
        
        // Agrupar por área temática
        const grupos = this.agruparPorArea(recomendaciones);
        
        // Mostrar por grupos
        for (const [area, programas] of Object.entries(grupos)) {
            const grupoDiv = this.crearGrupoArea(area, programas);
            cont.appendChild(grupoDiv);
        }
        
        // Configurar eventos para botones de detalle
        this.configurarBotonesDetalle();
    }

    agruparPorArea(recomendaciones) {
        const grupos = {};
        
        recomendaciones.forEach(programa => {
            const area = programa.sector_economico || 'General';
            if (!grupos[area]) grupos[area] = [];
            grupos[area].push(programa);
        });
        
        return grupos;
    }

    crearGrupoArea(area, programas) {
        const grupoDiv = document.createElement('div');
        grupoDiv.className = 'grupo-area';
        grupoDiv.innerHTML = `<h3 class="area-titulo">${area}</h3>`;
        
        programas.forEach(programa => {
            grupoDiv.appendChild(this.crearCardPrograma(programa));
        });
        
        return grupoDiv;
    }

    crearCardPrograma(programa) {
        const duracionMeses = (programa.duracion_programa / 48).toFixed(1);
        const isTecnologo = programa.nivel_formacion === 'Tecnologo';
        
        const card = document.createElement('div');
        card.className = 'recomendacion-card';
        card.dataset.nivel = programa.nivel_formacion;
        card.dataset.duracion = programa.duracion_programa;
        
        card.innerHTML = `
            <div class="card-header">
                <h3>${programa.nombre_programa}</h3>
                <span class="badge ${isTecnologo ? 'badge-tecnologo' : 'badge-tecnico'}">
                    ${programa.nivel_formacion}
                </span>
            </div>
            <div class="card-body">
                <p><i class="fas fa-clock"></i> <strong>Duración:</strong> ${duracionMeses} meses (${programa.duracion_programa} horas)</p>
                <p><i class="fas fa-briefcase"></i> <strong>Salida ocupacional:</strong> ${programa.nombre_ocupacion}</p>
                <p><i class="fas fa-calendar-alt"></i> <strong>Disponible hasta:</strong> ${new Date(programa.fecha_finalizacion).toLocaleDateString()}</p>
                <button class="btn-detalle" data-id="${programa.id}">
                    <i class="fas fa-info-circle"></i> Ver detalles completos
                </button>
            </div>`;
        
        return card;
    }

    configurarBotonesDetalle() {
        document.querySelectorAll('.btn-detalle').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const programaId = e.target.dataset.id;
                this.mostrarDetallePrograma(programaId);
            });
        });
    }

    mostrarDetallePrograma(programaId) {
        // Aquí podrías implementar la lógica para mostrar más detalles
        // Por ejemplo, abrir un modal o redirigir a otra página
        console.log('Mostrando detalles del programa ID:', programaId);
        // Ejemplo: window.location.href = `detalle-programa.php?id=${programaId}`;
    }

    async fetchData(action, extraData = {}) {
        const response = await fetch('../controllers/DiagnosticoController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: action, ...extraData })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }

    showSection(sectionId) {
        // Ocultar todas las secciones primero
        Object.values(this.elements).forEach(el => {
            if (el?.classList) el.classList.add('hidden');
        });
        
        // Mostrar la sección solicitada
        if (this.elements[sectionId]) {
            this.elements[sectionId].classList.remove('hidden');
        } else {
            document.getElementById(sectionId)?.classList.remove('hidden');
        }
    }

    showError(message, containerId = null) {
        const container = containerId ? 
            (this.elements[containerId] || document.getElementById(containerId)) : 
            this.elements.preguntasDinamicas;
        
        if (container) {
            container.innerHTML = `
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
                </div>`;
        } else {
            alert(message);
        }
    }

    showInfo(message, containerId) {
        const container = this.elements[containerId] || document.getElementById(containerId);
        if (container) {
            container.innerHTML = `
                <div class="info-message">
                    <i class="fas fa-info-circle"></i>
                    <p>${message}</p>
                </div>`;
        }
    }
}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    new DiagnosticoApp();
});

// Función global para volver atrás
function goBack() {
    window.history.back();
}
    </script>
</body>
</html>