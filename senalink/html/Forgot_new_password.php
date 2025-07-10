<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink - Nueva Contraseña</title>
    <link href="../css/crud.css" rel="stylesheet"/>
    <link rel="shortcut icon " href="../img/Favicon1.png"> <!-- corresponde al favicon en la pestaña -->
</head>
<body>
  <header class="gov" id="inicio">
        <div class="gov__container">
            <a href="https://www.gov.co/" target="_blank">
                <img loading="lazy" src="../img/gov-logo.svg" alt="Logo de la pagina gov.co" class="gov__img">
            </a>
        </div>
    </header>
    <header class="header__login">
        <div class="header__left">
            
        </div>

        <div class="header__center">
        </div>

        <div class="header__right">
            <img src="../img/logo-sena-white0.png" alt="Logo Izquierda" class="logo__sena">
        </div>
    </header>
    <div class="linea-verde"></div>
<div class="container">
  <main class="container__forms--forgot">
        <h2>Ingresa tu nueva contraseña</h2>
        <form action="../controllers/update_password.php" method="POST" id="newPasswordForm">
          <div class="box__password">
            <input type="password" name="new_password" placeholder="Nueva contraseña" required/>
            <button type="button" class="toggle-password-button">
              <img src="../img/icons8-invisible-24.png" alt="Mostrar/Ocultar contraseña" class="icon__pass" />
            </button>
          </div>
          <div class="box__password">
            <input type="password" name="confirm_password" placeholder="Confirmar nueva contraseña" required/>
            <button type="button" class="toggle-password-button">
              <img src="../img/icons8-invisible-24.png" alt="Mostrar/Ocultar contraseña" class="icon__pass" />
            </button>
          </div>
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token_recuperacion'] ?? '') ?>" />
          <div class="buttons__container">
              <button type="submit" class="btn">Confirmar</button>
              <a href="index.html" class="btn btn-secondary">Volver</a>
          </div>
      </form>
  </main>
  </div>         
  <footer>
    <p>© Todos los derechos reservados. SenaLink</p>
  </footer>
  <script src="../js/viewpassword.js"></script>
  <script>
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

      // Mostrar alerta si la contraseña fue cambiada exitosamente
      <?php if (isset($_SESSION['password_changed']) && $_SESSION['password_changed'] === true): ?>
        <?php unset($_SESSION['password_changed']); ?>
      <?php endif; ?>
    });
  </script>
</body>
</html>
