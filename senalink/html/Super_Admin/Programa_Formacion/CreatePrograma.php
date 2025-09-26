<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink - Editar Programa de Formación</title>
    
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

            <form action="../../../controllers/ProgramaController.php" 
                  method="POST" 
                  id="programaForm" 
                  class="form-layout validated-form">

                <input type="hidden" id="programa_id" name="id" value="">
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="origen" value="Super_Admin">

                <div class="form-group">
                    <label for="codigo">Código</label>
                    <input class="input-field" id="codigo" name="codigo" type="number" min="1" required
                        title="El código debe ser un número positivo."/>
                </div>

                <div class="form-group">
                    <label for="ficha">Ficha</label>
                    <input class="input-field" id="ficha" name="ficha" type="number" min="1" required
                        title="La ficha debe ser un número positivo."/>
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

                <div class="form-group">
                    <label for="etapa_ficha">Etapa Ficha</label>
                    <select class="input-field" id="etapa_ficha" name="etapa_ficha" required>
                        <option selected disabled value="">Etapa Ficha</option>
                        <option value="Lectiva">LECTIVA</option>
                        <option value="Practica">PRACTICA</option>
                    </select>
                </div>

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
                    <button type="submit" class="btn">Actualizar</button>
                    <button type="button" onclick="goBack()" class="btn">Volver</button>
                </div>
            </form>
        </main>
    </div>
    <script src="../../../js/backbutton.js"></script>
    <script src="../../../js/control_inactividad.js"></script>
    <script src="../js/alert.js"></script>
    <footer>
        <p>© Todos los derechos reservados. SenaLink</p>
    </footer>
</body>
</html>
