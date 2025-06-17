<?php session_start(); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>SenaLink - Nueva Contraseña</title>
    <link href="../css/forgot_new_password.css" rel="stylesheet"/>
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
  <header class="header__home">
    <button id="menu-toggle" class="menu-toggle">
      <img src="../icon/menu_24dp_FFFFFF_FILL0_wght400_GRAD0_opsz24.svg" alt="Menu Icon" />
    </button>
    <button class="account__button">
      <a href="../viewuser.html">
        <img src="../icon/account_circle_24dp_FFFFFF_FILL0_wght400_GRAD0_opsz24.svg" alt="icon" />
      </a>
    </button>
</header>
<div class="container">
  <main class="container__forms--forgot">
        <h2>Ingresa tu nueva contraseña</h2>
        <form action="../controllers/update_password.php" method="POST" id="newPasswordForm">
          <input type="password" name="new_password" placeholder="Nueva contraseña" required/>
          <input type="password" name="confirm_password" placeholder="Confirmar nueva contraseña" required/>
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token_recuperacion'] ?? '') ?>" />
          <div class="buttons__container">
              <button type="submit" class="btn">Confirmar</button>
              <a href="index.html" class="btn btn-secondary">Volver</a>
          </div>
      </form>
  </main>
  </div>         
  <div class="linea-azul-difuminada"></div>
  <footer>
    <p>@ Todos los derechos reservados. SenaLink</p>
  </footer>
</body>
</html>
