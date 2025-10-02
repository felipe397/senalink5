<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink - Crear Programa de Formación</title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined&display=swap" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../../css/alert.css" />
    <link rel="stylesheet" href="../../../css/components/gov.css" />
    <link rel="stylesheet" href="../../../css/components/buttons.css" />
    <link rel="stylesheet" href="../../../css/layouts/Form_layout.css" />
    <link rel="stylesheet" href="../../../css/base.css" />
    <link rel="shortcut icon" href="../../../img/Favicon1.png" />
</head>

<body>
    <!-- Header GOV -->
    <header class="gov" id="inicio">
        <div class="gov__container">
            <a href="https://www.gov.co/" target="_blank">
                <img loading="lazy" src="../../../img/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img" />
            </a>
        </div>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <main class="container__crud">
            <img src="../../../img/logo-proyecto1.png" alt="Logo SenaLink" class="logo__senalink"/>

            <!-- Removí action y method del form para prevenir submits nativos -->
            <form id="programaForm" class="form-layout validated-form">

                <input type="hidden" id="programa_id" name="id" value="">
                <input type="hidden" name="accion" value="crear">
                <input type="hidden" name="origen" value="Super_Admin">

                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input class="input-field" id="codigo" name="codigo" type="text" minlength="3" pattern="\d{3,}" required
                        title="El código debe ser un número positivo con al menos 3 dígitos."/>
                </div>

                <div class="form-group">
                    <label for="ficha">Ficha</label>
                    <input class="input-field" id="ficha" name="ficha" type="text" minlength="3" maxlength="7" pattern="\d{3,7}" required
                        title="La ficha debe ser un número positivo con entre 3 y 7 dígitos."/>
                </div>

                <div class="form-group">
                    <label for="nivel_formacion">Nivel de formación</label>
                    <select class="input-field" id="nivel_formacion" name="nivel_formacion" required>
                        <option value="" disabled selected>Nivel de formación</option>
                        <option value="TECNICO">TECNICO</option>
                        <option value="TECNOLOGO">TECNOLOGO</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sector_programa">Sector del programa</label>
                    <select class="input-field" id="sector_programa" name="sector_programa" required>
                        <option selected disabled value="">Sector del programa</option>
                        <option value="INDUSTRIAL">INDUSTRIAL</option>
                        <option value="SERVICIOS">SERVICIOS</option>
                    </select>
                </div>

                <!-- Removed etapa_ficha select to force default LECTIVA in backend -->

                <div class="form-group">
                    <label for="sector_economico">Sector Económico</label>
                    <select class="input-field" id="sector_economico" name="sector_economico" required>
                        <option selected disabled value="">Sector Económico</option>
                        <option value="Industria">INDUSTRIA</option>
                        <option value="Servicios">SERVICIOS</option>
                        <option value="Textiles">TEXTILES</option>
                        <option value="Construccion">CONSTRUCCIÓN</option>
                        <option value="Electricidad">ELECTRICIDAD</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nombre_programa">Nombre del programa</label>
                    <input class="input-field" id="nombre_programa" name="nombre_programa" 
                           type="text" placeholder="Nombre del programa" required  
                           pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s.]+" 
                           title="El nombre solo debe contener letras, espacios y puntos."/>
                </div>

                <div class="form-group">
                    <label for="nombre_ocupacion">Nombre de Ocupación</label>
                    <input class="input-field" id="nombre_ocupacion" name="nombre_ocupacion" 
                           type="text" placeholder="Nombre de Ocupación" 
                           required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s.]+" 
                           title="El nombre solo debe contener letras, espacios y puntos." />
                </div>

                <div class="form-group">
                    <label for="duracion_programa">Duración (Horas)</label>
                    <input class="input-field" id="duracion_programa" name="duracion_programa" 
                           type="number" min="1" required 
                           placeholder="Duración en horas"
                           title="La duración debe ser un número positivo." />
                </div>

                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="input-field" id="estado" name="estado" required>
                        <option value="" disabled selected>Estado del programa</option>
                        <option value="En ejecucion">En ejecucion</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_finalizacion">Fecha de finalización</label>
                    <input class="input-field" id="fecha_finalizacion" name="fecha_finalizacion" 
                           type="date" required min="1957-06-21"
                           title="La fecha no puede ser anterior al 21 de junio de 1957."/>
                </div>

                <div class="btn__container">
                    <button type="button" id="btnCrear" class="btn">Crear</button>  <!-- Cambié a type="button" para prevenir submit nativo -->
                    <button type="button" onclick="goBack()" class="btn">Volver</button>
                </div>
            </form>
        </main>
    </div>
    <script src="../../../js/backbutton.js"></script>
    <script src="../../../js/alert.js"></script>
    <script>
        let isSubmitting = false;  // Prevenir doble envío

        // Función para calcular la fecha de finalización automática (sin cambios)
        function calcularFechaFinalizacion() {
            const duracionInput = document.getElementById('duracion_programa');
            const fechaInput = document.getElementById('fecha_finalizacion');
            const duracion = parseInt(duracionInput.value);

            if (!duracion || duracion < 1) {
                fechaInput.value = '';
                return;
            }

            let fechaInicio = new Date();
            fechaInicio.setHours(0, 0, 0, 0);

            const horasPorDia = 6;
            const diasNecesarios = Math.ceil(duracion / horasPorDia);

            let diasContados = 0;
            let fechaActual = new Date(fechaInicio);

            while (diasContados < diasNecesarios) {
                fechaActual.setDate(fechaActual.getDate() + 1);
                const diaSemana = fechaActual.getDay();
                if (diaSemana !== 0 && diaSemana !== 6) {
                    diasContados++;
                }
            }

            const año = fechaActual.getFullYear();
            const mes = String(fechaActual.getMonth() + 1).padStart(2, '0');
            const dia = String(fechaActual.getDate()).padStart(2, '0');
            fechaInput.value = `${año}-${mes}-${dia}`;
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM cargado, adjuntando listeners...');  // Log para confirmar que se ejecuta

            // Listener para fecha (sin cambios)
            const duracionInput = document.getElementById('duracion_programa');
            duracionInput.addEventListener('input', calcularFechaFinalizacion);
            duracionInput.addEventListener('change', calcularFechaFinalizacion);

            // CAMBIO CLAVE: Listener en el BOTÓN (no en el form) para control total
            const btnCrear = document.getElementById('btnCrear');
            btnCrear.addEventListener('click', function (event) {
                console.log('Primer clic detectado en botón Crear - Iniciando envío AJAX');  // Log para confirmar primer clic

                if (isSubmitting) {
                    console.log('Envío ya en progreso, ignorando.');
                    return;
                }

                const form = document.getElementById('programaForm');
                const formData = new FormData(form);

                // Log de datos (para depurar)
                console.log('Datos enviados al backend:');
                for (let [key, value] of formData.entries()) {
                    console.log(key + ': ' + value);
                }

                // Chequeo de corrupción (HTML en datos)
                let hasHtmlCorruption = false;
                for (let [key, value] of formData.entries()) {
                    if (typeof value === 'string' && (value.includes('<!DOCTYPE') || value.includes('<html>') || value.length > 1000)) {  // Ajusté para longitudes sospechosas
                        hasHtmlCorruption = true;
                        console.error('¡CORRUPCIÓN DETECTADA en campo:', key, 'Valor parcial:', value.substring(0, 200));
                        showAlert('Error: Datos inválidos detectados. Verifica el formulario.', 'error');
                        return;
                    }
                }

                isSubmitting = true;
                btnCrear.disabled = true;
                btnCrear.textContent = 'Creando...';

                // URL del backend (hardcodeada ya que removí action del form)
                const urlBackend = '../../../controllers/ProgramaController.php';

                fetch(urlBackend, {
                    method: 'POST',
                    body: formData,
                })
                .then((response) => {
                    const contentType = response.headers.get('content-type');
                    console.log('Content-Type de respuesta:', contentType);  // Log para depurar
                    if (contentType && contentType.indexOf('application/json') !== -1) {
                        return response.json();
                    } else {
                        return response.text().then(text => {
                            console.error('Respuesta NO JSON (posible HTML):', text.substring(0, 500));
                            throw new Error('El servidor no devolvió JSON válido. Posible error en backend.');
                        });
                    }
                })
                .then((data) => {
                    console.log('Respuesta procesada:', data);
                    
                    if (data.success) {
                        showAlert('Programa creado correctamente', 'success');
                        setTimeout(() => {
                            window.location.href = 'Gestion_Programa.html';
                        }, 2000);
                    } else if (data.errors) {
                        const mensajes = data.errors.join('<br>');
                        showAlert(mensajes, 'error');
                    } else if (data.error) {
                        showAlert(data.error, 'error');
                    } else {
                        showAlert('Error desconocido al crear el programa', 'error');
                    }
                })
                .catch((error) => {
                    console.error('Error en fetch:', error);
                    showAlert('Error en la comunicación: ' + error.message, 'error');
                })
                .finally(() => {
                    isSubmitting = false;
                    btnCrear.disabled = false;
                    btnCrear.textContent = 'Crear';
                });
            });
        });
    </script>
    <footer>
        <p>© Todos los derechos reservados. SenaLink</p>
    </footer>
</body>
</html>
