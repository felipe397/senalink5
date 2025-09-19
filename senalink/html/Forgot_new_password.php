
<?php session_start(); ?>
<!--
  Archivo: Forgot_new_password.php
  Descripción: Página para que el usuario ingrese y confirme una nueva contraseña tras recuperación.
  Incluye formulario, validaciones y scripts para mostrar/ocultar contraseña.
-->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink - Nueva Contraseña</title>
    <!-- Hoja de estilos principal -->
    <link href="../css/crud.css" rel="stylesheet"/>
    <link href="../css/alert.css" rel="stylesheet"/>
    <!-- Favicon de la pestaña -->
    <link rel="shortcut icon " href="../img/Favicon1.png"> <!-- corresponde al favicon en la pestaña -->
</head>
<body>
  <!-- Encabezado de gobierno -->
  <header class="gov" id="inicio">
        <div class="gov__container">
            <a href="https://www.gov.co/" target="_blank">
                <img loading="lazy" src="../img/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img">
            </a>
        </div>
    </header>
    <!-- Encabezado principal con logo SENA -->
    <header class="header__login">
        <div class="header__left">
            <!-- Espacio reservado para elementos a la izquierda del header -->
        </div>
        <div class="header__center">
            <!-- Espacio reservado para elementos centrales del header -->
        </div>
        <div class="header__right">
            <img src="../img/logo-sena-white0.png" alt="Logo Izquierda" class="logo__sena">
        </div>
    </header>
    <!-- Contenedor principal -->
    <div class="container">
      <main class="container__forms--forgot">
            <h2>Ingresa tu nueva contraseña</h2>
            <!-- Formulario para ingresar y confirmar la nueva contraseña -->
            <form action="../controllers/update_password.php" method="POST" id="newPasswordForm">
              <div class="box__password">
                <!-- Campo para la nueva contraseña -->
                <input type="password" name="new_password" placeholder="Nueva contraseña" required/>
                <!-- Botón para mostrar/ocultar contraseña -->
                <button type="button" class="toggle-password-button">
                  <img src="../img/icons8-invisible-24.png" alt="Mostrar/Ocultar contraseña" class="icon__pass" />
                </button>
              </div>
              <div class="box__password">
                <!-- Campo para confirmar la nueva contraseña -->
                <input type="password" name="confirm_password" placeholder="Confirmar nueva contraseña" required/>
                <!-- Botón para mostrar/ocultar contraseña -->
                <button type="button" class="toggle-password-button">
                  <img src="../img/icons8-invisible-24.png" alt="Mostrar/Ocultar contraseña" class="icon__pass" />
                </button>
              </div>
              <!-- Token oculto para validar la recuperación -->
              <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token_recuperacion'] ?? '') ?>" />
              <div class="buttons__container">
                  <!-- Botón para confirmar el cambio de contraseña -->
                  <button type="submit" class="btn">Confirmar</button>
                  <!-- Botón para volver al inicio -->
                  <a href="index.html" class="btn btn-secondary">Volver</a>
              </div>
          </form>
      </main>
    </div>         
    <!-- Pie de página -->
    <footer>
      <p>© Todos los derechos reservados. SenaLink</p>
    </footer>
    <!-- Script para mostrar/ocultar contraseña -->
    <script src="../js/viewpassword.js"></script>
    <script src="../js/alert.js"></script>
    <script src="../js/control_inactividad.js"></script>
    <script>
    // Script para mostrar/ocultar la contraseña en los campos
    document.addEventListener("DOMContentLoaded", function () {
      function setupTogglePassword(inputName, buttonClass) {
        const input = document.querySelector('input[name="' + inputName + '"]');
        const toggleButtons = document.querySelectorAll('.' + buttonClass);
        toggleButtons.forEach(function(button) {
          button.addEventListener('click', function() {
            const icon = button.querySelector('img');
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            icon.src = isPassword ? "../img/icons8-eye-24.png" : "../img/icons8-invisible-24.png";
          });
        });
      }
      setupTogglePassword('new_password', 'toggle-password-button');
      setupTogglePassword('confirm_password', 'toggle-password-button');

      // Manejar el envío del formulario por AJAX para mostrar alertas con showAlert
      const form = document.getElementById('newPasswordForm');
      form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        fetch(form.action, {
          method: 'POST',
          body: formData
        })
        .then(async response => {
          let data;
          try {
            data = await response.json();
          } catch (e) {
            // Si la respuesta no es JSON, intentar extraer texto plano
            const text = await response.text();
            showAlert(text || 'Error en la comunicación con el servidor', 'error');
            return;
          }
          if (data.success) {
            showAlert('Contraseña cambiada exitosamente', 'success');
            setTimeout(() => {
              window.location.href = data.redirect || '../html/index.php';
            }, 1200);
          } else {
            showAlert(data.error || 'Error desconocido', 'error');
          }
        })
        .catch(async err => {
          // Si hay error de red, intentar mostrar el mensaje del backend si existe
          try {
            const response = err && err.response;
            if (response) {
              const text = await response.text();
              showAlert(text || 'Error en la comunicación con el servidor', 'error');
            } else {
              showAlert('Error en la comunicación con el servidor', 'error');
            }
          } catch {
            showAlert('Error en la comunicación con el servidor', 'error');
          }
        });
      });
    });
    </script>
</body>
</html>
