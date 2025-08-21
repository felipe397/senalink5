<!DOCTYPE html>
<!-- Página para la creación de programas de formación en SenaLink (AdminSENA) -->
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink - Crear Programa de formación</title>
    <!-- Fuentes, iconos y estilos principales del formulario -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../../css/crud.css">
    <link rel="stylesheet" href="../../../css/alert.css">
    <link rel="shortcut icon" href="../../../img/Favicon1.png">
    <!-- Estilo local para mostrar mensajes de error de validación -->
    <style>
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <!-- Encabezado institucional de GOV.CO -->
    <header class="gov" id="inicio">
        <div class="gov__container">
            <a href="https://www.gov.co/" target="_blank">
                <img loading="lazy" src="../../../img/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img">
            </a>
        </div>
    </header>

    <!-- Header superior, logo SENA a la derecha -->
    <header class="header__login">
        <div class="header__left"></div>
        <div class="header__center"></div>
        <div class="header__right">
            <img src="../../../img/logo-sena-white0.png" alt="Logo SENA" class="logo__sena">
        </div>
    </header>

    <!-- Contenedor principal del formulario de creación de programa -->
    <div class="container">
        <main class="container__crud">
            <img alt="SenaLink Logo" src="../../../img/logo-proyecto1.png" class="logo__senalink"/>

            <!-- Muestra mensaje de error si hubo problema creando el programa -->
            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">❌ Error al crear el programa. Verifique los datos o intente de nuevo.</div>
            <?php endif; ?>

            <form action="../../../controllers/ProgramaController.php" method="POST">
                <input type="hidden" name="accion" value="crear" />
                <div class="inputs__container">

                    <!-- Campo: código del programa (numérico, positivo) -->
                    <input class="input-field" name="codigo" placeholder="Código" type="number" min="1" required 
                        title="El código debe ser un número positivo."/>

                    <!-- Campo: ficha (identificación única SENA) -->
                    <input class="input-field" name="ficha" placeholder="Ficha" type="number" min="1" required 
                        title="La ficha debe ser un número positivo."/>

                    <!-- Selector: nivel de formación -->
                    <select class="select_container" name="nivel_formacion" required>
                        <option selected disabled value="">Nivel de formación</option>
                        <option value="TECNICO">TECNICO</option>
                        <option value="TECNOLOGO">TECNOLOGO</option>
                    </select>

                    <!-- Selector: sector del programa (industrial, servicios) -->
                    <select class="select_container" name="sector_programa" required>
                        <option selected disabled value="">Sector del programa</option>
                        <option value="INDUSTRIAL">INDUSTRIAL</option>
                        <option value="SERVICIOS">SERVICIOS</option>
                    </select>

                    <!-- Campo: nombre del programa de formación -->
                    <input class="input-field" name="nombre_programa" placeholder="Nombre del programa" type="text" required 
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s.]+" 
                        title="El nombre solo debe contener letras, espacios y puntos."/>

                    <!-- Campo: nombre de la ocupación a la que pertenece el programa -->
                    <input class="input-field" name="nombre_ocupacion" placeholder="Nombre de Ocupación" type="text" required 
                        pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s.]+" 
                        title="El nombre solo debe contener letras, espacios y puntos."/>

                    <!-- Campo: duración del programa en horas -->
                    <input class="input-field" name="duracion_programa" placeholder="Duración (Horas)" type="number" min="1" required 
                        title="La duración debe ser un número positivo."/>

                    <!-- Selector de estado del programa -->
                    <select name="estado" required class="select_container">
                        <option selected disabled value="">Estado del programa</option>
                        <option value="En ejecucion">En ejecución</option>
                        <option value="Finalizado">Finalizado</option>
                    </select>

                    <!-- Selector de etapa de la ficha: Lectiva o Práctica -->
                    <select name="etapa_ficha" required class="select_container">
                        <option selected disabled value="">Etapa del programa</option>
                        <option value="Lectiva">Lectiva</option>
                        <option value="Practica">Práctica</option>
                    </select>

                    <!-- Selector de sector económico -->
                    <select class="select_container" name="sector_economico" required>
                        <option selected disabled value="">Sector Económico</option>
                        <option value="Industria">Industria</option>
                        <option value="Servicios">Servicios</option>
                        <option value="Textiles">Textiles</option>
                        <option value="Construccion">Construccion</option>
                        <option value="Electricidad">Electricidad</option>
                    </select>

                    <!-- Campo: fecha finalización del programa -->
                    <input class="input-field" name="fecha_finalizacion" placeholder="Fecha de finalización" type="date" required 
                        min="1957-06-21"
                        title="La fecha no puede ser anterior al 21 de junio de 1957."/>
                </div>

                <div class="buttons__container">
                    <!-- Botón para crear el programa -->
                    <button type="submit" class="buttons__crud">Crear</button>
                    <!-- Botón para volver a la pantalla anterior -->
                    <button onclick="goBack()" class="buttons__crud" type="button">Volver</button>
                </div>
            </form>
        </main>
    </div>

    <!-- Scripts: botón volver, control de inactividad y pie de página -->
    <script src="../../../js/backbutton.js"></script>
    <script src="../js/control_inactividad.js"></script>
    <script src="../js/alert.js"></script>

    <footer>
        <p>© Todos los derechos reservados. SenaLink</p>
    </footer>
</body>
</html>
>
